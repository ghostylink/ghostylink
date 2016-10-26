<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use Cake\View\View;
use App\Model\Entity\Link;
use App\Model\Entity\User;

/**
 * CakePHP ViewsLimitHelper
 * @author kremy
 */
class ViewsLimitHelper extends LinkHelper implements LinkComponentHelper
{
    protected $config = [
        'summaryTemplate' => 'The link will be destroyed after {value} view(s)',
        'icon' => 'glyphicon glyphicon-eye-open',
        'type' => 'link-life',
        'relatedField' => 'max_views',
        'label' => 'Views limit',
        'description' => 'Destroy content after the specified number of views'
    ];

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $this->config);
    }

    public function field(Link $link = null, array $user = null)
    {
        return $this->Form->input(
            'max_views',
            ['type' => 'number',
            'id' => 'inputContent',
            'class' => 'form-control',
            'placeholder' => "Enter your links life expectancy (number of views)",
            'required' => 'true']
        );
    }

    public function getValue(Link $link)
    {
        return $link->max_views . ' views';
    }
}
