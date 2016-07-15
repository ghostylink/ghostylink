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
class ViewsLimitHelper extends Helper
{
    public $helpers = ["Html", "Form"];

    public function display(Link $link)
    {
        echo $this->Html->tag(
            "li",
            ' ',
            array_merge(
                ['class' => 'glyphicon glyphicon-eye-open',
                 'data-type' => 'link-life',
                 'data-related-field' => 'max_views',
                 'data-summary-template' => 'The link will be destroyed after {value} view(s)',
                 'escape' => false],
                []
            )
        );
    }
    
    public function displayField(Link $link)
    {
        echo $this->Form->input(
            'max_views',
            ['type' => 'number',
            'id' => 'inputContent',
            'class' => 'form-control',
            'placeholder' => "Enter your links life expectancy (number of views)",
            'required' => 'false']
        );
    }
}
