<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Core\Configure;

class LinkMailer extends Mailer
{

    /**
     * Send notifications for nearly ghostyified links
     * @param Entity $user The user to send notification to
     * @param Array<Entity> $links links to include in the mail
     */
    public function notification($user, $links)
    {
        $installedUrl = Configure::read("App.fullBaseUrl");
        $urlComponents = parse_url($installedUrl);
        $ghostylinkHost = $urlComponents["host"];
        $this->helpers(['EmailProcessing']);
        $this->transport('default');
        $this
            ->to($user->email)
            ->from("notifications@$ghostylinkHost")
            ->emailFormat('html')
            ->subject('Ghostification alert')
            ->set(['links' => $links]);
    }
}
