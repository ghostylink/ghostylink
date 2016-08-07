<?php
/*
 * A helper utility to manage date limit link components
 */

namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;
use App\Model\Entity\User;

/**
 * Date time helper implementation
 * @author kremy
 */
class DateLimitHelper extends LinkHelper implements LinkComponentHelper
{
    public $helpers = ["Html", "Form"];

    protected $config = [
        'summaryTemplate' => 'The link will be destroyed at {value}',
        'icon' => 'glyphicon glyphicon-calendar',
        'type' => 'link-life',
        'relatedField' => 'death_date',
        'label' => 'Date limit',
        'description' => 'Destroy content at the given date'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
    }

    public function field(Link $link = null, array $user = null)
    {
        return $this->Form->input(
            'death_date',
            [
             'type' => 'text',
             'id' => 'death_date',
             'class' => 'form-control',
             'placeholder' => "Link death date",
             'required' => 'false'
            ]
        );
    }

    public function getValue(Link $link)
    {
        return $link->death_time->i18nFormat();
    }
}
