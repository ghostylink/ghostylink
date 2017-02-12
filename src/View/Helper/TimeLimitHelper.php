<?php

/*
 * An helper utility to manage death time link components
 */

namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;
use App\Model\Entity\User;

/**
 * Helper class for the time limit helper
 * @author kremy
 */
class TimeLimitHelper extends LinkHelper implements LinkComponentHelper
{
    protected $config = [
        'summaryTemplate' => 'The link will be destroyed after {value} day(s)',
        'icon' => 'glyphicon glyphicon-time',
        'type' => 'link-life',
        'relatedField' => 'death_time',
        'label' => 'Time limit',
        'description' => 'Destroy content after the specified time'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
    }

    public function field(Link $link = null, array $user = null)
    {
        $label = $this->Html->tag("label", 'Time before deletion :') .
                $this->Html->tag("br");
        $options = array(
            ['text' => '1 day', 'value' => 1, 'checked' => 'checked'],
            ['text' => '1 week', 'value' => 7],
            ['text' => '1 month', 'value' => 30]
        );
        $attributes = ['nestedInput' => false];

        $this->Form->templates(
            ['nestingLabel' => '<label {{attrs}} class="btn btn-default">{{text}}</label>']
        );
        $radioHTML = $this->Form->radio('death_time', $options, $attributes);
        return $this->Html->tag(
            "div",
            $label . $radioHTML,
            ['id' => 'death_time', 'class' => ['input', 'btn-group']]
        );
    }

    public function getValue(Link $link)
    {
        return $link->time_limit;
    }
}
