<?php

namespace App\Shell;

use Cake\Console\Shell;
use Psr\Log\LogLevel;
use Cake\Core\Configure;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception\SocketException;

/**
 * Class to send email from the command line interface
 */
class MailerShell extends Shell
{

    use MailerAwareTrait;
    /**
     * Initialize the shell mailer
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('AlertParameters');
        $this->loadModel('Links');
    }

    /**
     * Define actions in the mailer command
     * @return Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('alerts', [
            'help' => 'Send mail alert to users who have at least one nearly ghostified link'
        ]);
        return $parser;
    }

    /**
     * Send mail alert to users who have at least one nearly ghostified link
     */
    public function alerts()
    {
        $host = Configure::read("EmailTransport.default.host");

        if (!isset($host)) {
            $this->log("Mailing is not configured. Skipping alerts sending", LogLevel::INFO);
            return;
        }

        $users = $this->Users->find('needMailAlert')->all();
        foreach ($users as $user) {
            $updatedIds = [];
            $links = $this->Links->find('needMailAlert')->contain('AlertParameters')
                                              ->where(['user_id' => $user->id])->all();

            foreach ($links as $link) {
                array_push($updatedIds, $link->id);
            }

            $params = [$user, $links];
            
            try {
                $this->getMailer('Link')->send('notification', $params);
            } catch (SocketException $ex) {
                $this->log("Sending mail to {$user->email} failed.\n $ex");
                return;
            }
            $alertParams = $this->AlertParameters->query();
            $alertParams->param['updatedIds'] = $updatedIds;

            $alertParams->update()
                    ->set(['sending_status' => true])
                    ->where(function ($exp, $q) {
                        return $exp->in('link_id', $q->param['updatedIds']);
                    });
            $alertParams->execute();
        }
    }
}
