<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use Cake\View\Helper;
use App\Model\Entity\Link;

/**
 * CakePHP ViewsLimitHelper
 * @author kremy
 */
class GoogleCaptchaHelper extends Helper
{
    public $helpers = ["Html", "Form"];

    public function display(Link $link)
    {
        echo $this->Html->tag(
            "li",
            '_',
            ['class' => 'glyphicon glyphicon-recaptcha',
             'data-type' => 'protection',
             'data-summary-template' => 'The link will protected against bots by Google ReCaptcha',
             'escape' => false]
        );
    }
    
    public function displayField(Link $link)
    {
        echo $this->Form->hidden('google_captcha', ['value' => true]);
    }
}
