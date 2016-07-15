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
 * CakePHP GhostyficationHelper
 * @author kremy
 */
class GhostyficationAlertHelper extends Helper {
    public $helpers = ["Html", "Form"];

    public function display(Link $link)
    {
        echo $this->Html->tag(
            "li",
            '',
            ['class' => 'glyphicon glyphicon-bell',
             'data-type' => 'misc',
             'data-related-field' => htmlspecialchars('alert_parameter["life_threshold"]'),
             'data-summary-template' => 'You will be warn by email at {value} % of the link life',
             'escape' => false]
        );
    }

    public function displayField(Link $link)
    {

        $field = $this->Form->input(
            'alert_parameter.life_threshold',
            ['id' => 'default_threshold',
            'label' => false,
            'placeholder' => "Default link alert life threshold",
            'readonly' => true,
            'type' => 'text',
            'value' => $link->alert_parameter->life_threshold,
            'required' => 'false']
        );
        $label = $this->Html->tag("label", "Life percentage alert threshold", ["for" => "default_threshold"]);
        $slider = $this->Html->tag("div", '', ['id' => 'slider-default_threshold']);
        echo $this->Html->tag("div", $label . $slider . $field);

    }
}
