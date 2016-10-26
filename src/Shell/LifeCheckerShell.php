<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\Mailer\Email;
use Cake\Mailer\MailerAwareTrait;
use Cake\Network\Exception\SocketException;

/**
 * Class to send email from the command line interface
 */
class LifeCheckerShell extends Shell
{

    /**
     *
     * @var \App\Model\Table\LinksTable
     */
    public $Links = null;

    /**
     * Initialize the shell mailer
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Links');
    }

    /**
     * Define actions in the mailer command
     * @return Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('delete_dead', [
            'help' => 'Delete all links which have reached 100% of their life'
        ]);
        return $parser;
    }

    public function deleteDead()
    {
        $find = $this->Links->find(
            "rangeLife",
            ["min_life" => 100, "max_life" => 100]
        )->delete();
        $find->execute();
    }
}
