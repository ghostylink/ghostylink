<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class LinkMailer extends Mailer
{

    /**
     * TODO : remove this to do
     * Send notifications for nearly ghostyified links
     * @param Entity $user The user to send notification to
     * @param Array<Entity> $links links to include in the mail
     */
    public function notification($user, $links)
    {
        $this->helpers(['EmailProcessing']);
        $this->transport('default');
        $this
            ->to($user->email)
            ->from('notifications@ghostylink.org')
            ->emailFormat('html')
            ->subject('Ghostification alert')
            ->set(['links' => $links]);
    }
}
