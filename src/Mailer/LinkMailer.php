<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class LinkMailer extends Mailer
{

    /**
     * Send notifications for nearly ghostyified links
     * @param Entity $user The user to send notification to
     * @param Array<Entity> $links links to include in the mail
     */
    public function notification($user, Array $links)
    {
        $this->transport('default');
        $this
            ->to($user->email)
            ->from('notifications@ghostylink.org')
            ->emailFormat('html')
            ->subject('Ghostification alert')
            ->set(['links' => $links]);
    }
}