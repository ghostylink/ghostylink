<?php
/*
 * Helper implementation for the google captcha component
 */
namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;

/**
 * Google captcha component helper
 * @author kremy
 */
class GoogleCaptchaHelper extends LinkHelper implements LinkComponentHelper
{

    protected $config = [
        'summaryTemplate' => 'The link will protected against bots by Google ReCaptcha',
        'icon' => 'glyphicon glyphicon-recaptcha',
        'type' => 'protection',
        'relatedField' => 'google_captcha',
        'content' => '_'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
    }

    public function field(Link $link)
    {
        return $this->Form->hidden('google_captcha', ['value' => true]);
    }
}