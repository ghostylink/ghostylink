<?php

/*
 * An helper utility to manage death time link components
 */

namespace App\View\Helper;

use Cake\View\Helper;
use App\Model\Entity\Link;

/**
 * CakePHP ViewsLimitHelper
 * @author kremy
 */
class DeathTimeHelper extends Helper
{
    public $helpers = ["Html", "Form"];

    public function display(Link $link)
    {
        echo $this->Html->tag(
            "li",
            '',
            ['class' => 'glyphicon glyphicon-time',
             'data-type' => 'link-life',
             'data-related-field' => 'death_time',
             'data-summary-template' => 'The link will be destroyed after {value} day(s)',
             'escape' => false]
        );
    }

    public function displayField(Link $link)
    {
        $options = array(
            ['text' => '1 day', 'value' => 1, 'checked' => 'checked'],
            ['text' => '1 week', 'value' => 7],
            ['text' => '1 month', 'value' => 30]
        );

        $attributes = ['nestedInput' => false];

        $this->Form->templates(
            ['nestingLabel' => '<label {{attrs}}>{{text}}</label>']
        );
        $radioHTML = $this->Form->radio('death_time', $options, $attributes);
        echo $this->Html->tag(
            "div",
            $radioHTML,
            ['id' => 'id_death_time', 'class' => 'input']
        );
    }
}
