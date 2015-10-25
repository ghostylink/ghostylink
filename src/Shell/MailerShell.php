<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Mailer\Email;

/**
 * Class to send email from the command line interface
 */
class MailerShell extends Shell
{

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
        $alertParams = $this->AlertParameters->query();
        foreach ($this->Users->find('needMailAlert')->all() as $user) {
            $updatedIds = [];
            $email = new Email('default');
            $links = $this->Links->find('needMailAlert')->where(['user_id' => $user->id])->all();

            foreach ($links as $link) {
                array_push($updatedIds, $link->id);
            }

            $email->from(['me@example.com' => 'My Site'])
                    ->to($user->email)
                    ->subject('You have links nearly ghostified')
                    ->template('notification')
                    ->emailFormat('html')
                    ->viewVars(['links' => $links])
                    ->send('My message');

            $alertParams->param['updatedIds'] = $updatedIds;
            $alertParams->update()
                    ->set(['sending_status' => true])
                    ->where(function ($exp, $q) {
                        return $exp->in('link_id', $q->param['updatedIds']);
                    });
            $alertParams->execute();
            
            // TODO log email sending if success
            $this->out($user->email);
        }
    }

}
