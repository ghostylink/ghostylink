<?php
namespace App\Mailer;

use Cake\Event\EventListenerInterface;
use Cake\Mailer\Mailer;
use Cake\Event\Event;
use Cake\ORM\Entity;

class UserMailer extends Mailer implements EventListenerInterface
{
    /**** Singleton design pattern ****/
    protected static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __clone()
    {
        ; //do nothing
    }

    public function implementedEvents()
    {
        return [
            'Model.afterSave' => 'onRegistration'
        ];
    }

    public function onRegistration(Event $event, Entity $entity, \ArrayObject $options)
    {
        if ($entity->email != null) {
            if ($entity->isNew()) {
                $this->send('emailConfirmation', [$entity]);
            } elseif ($entity->dirty('email')) {
                    $this->set(['user' => $entity]);
                    $this->send('emailConfirmation', [$entity]);
                    debug("Email has changed !");
            }
        }
    }
    /**
     * Send an email to ask confirmation on the email validity
     * @param Entity $user The user to send a email to
     */
    public function emailConfirmation($user)
    {
        $this->helpers(['Html', 'EmailProcessing', 'Url']);
        $this->transport('default');
        $this
            ->to($user->email)
            ->from('notifications@ghostylink.org')
            ->emailFormat('html')
            ->subject('Ghostylink email verification')
            ->set(['user' => $user]);
    }
}
