<?php
/*
 * Helper implementation for the google captcha component
 */
namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;
use App\Model\Entity\User;

/**
 * Google captcha component helper
 * @author kremy
 */
class GoogleCaptchaHelper extends LinkHelper implements LinkComponentHelper
{

    protected $config = [
        'summaryTemplate' => 'The link will be protected against bots by Google ReCaptcha',
        'icon' => 'glyphicon glyphicon-ok-circle',
        'type' => 'protection',
        'relatedField' => 'google_captcha',
        'label' => 'Google captcha',
        'description' => 'Protect content from bot by the ReCaptcha system'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
    }

    public function field(Link $link = null, array $user = null)
    {
        $content = $this->Html->tag(
            "div",
            $this->Form->hidden('google_captcha', ['value' => true]),
            ['class' => 'hidden']
        );
        return $content;
    }
}
