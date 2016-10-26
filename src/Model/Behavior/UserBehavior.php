<?php
namespace App\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

class UserBehavior extends Behavior
{
    protected $_defaultConfig = [
        'email' => 'email',
        'email_validation_link' => 'email_validation_link'
    ];

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {
        if (array_key_exists('email', $data)) {
            if ($data['email'] == '') {
                $data['email'] = null;
            }
        }
    }

    /**
     * Event triggered after the user validation and before saving the object
     * @param Event $event
     * @param \ArrayObject $data
     * @param \ArrayObject $options
     */
    public function beforeSave(Event $event, Entity $entity)
    {
        if ($entity->isNew() || $entity->getOriginal("email") !=  $entity->email) {
            $salt = rand(0, 65535) . time();
            $entity->email_validation_link = base64_encode(sha1(uniqid($salt, true)));
            $entity->email_validated = false;
        }
    }
}
