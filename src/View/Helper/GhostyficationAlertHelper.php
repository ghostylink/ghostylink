<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;

/**
 * Ghostyfication alert helper implementation
 * @author kremy
 */
class GhostyficationAlertHelper extends LinkHelper implements LinkComponentHelper
{
    protected $config = [
        'summaryTemplate' => 'You will be warn at {value} % of the link life',
        'icon' => 'glyphicon glyphicon-bell',
        'type' => 'misc',
        'relatedField' => 'alert_parameter["life_threshold"]'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
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
