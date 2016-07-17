<?php

/*
 * An helper utility to manage death time link components
 */

namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;

/**
 * Helper class for the death time helper
 * @author kremy
 */
class TimeLimitHelper extends LinkHelper implements LinkComponentHelper
{
    public $helpers = ["Html", "Form"];

    protected $config = [
        'summaryTemplate' => 'The link will be destroyed after {value} day(s)',
        'icon' => 'glyphicon glyphicon-time',
        'type' => 'link-life',
        'relatedField' => 'death_time'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
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
