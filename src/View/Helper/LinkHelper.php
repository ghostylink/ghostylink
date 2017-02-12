<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use App\Model\Entity\Link;
use App\Model\Entity\User;
use Cake\View\Helper;
use Cake\View\View;

/**
 * CakePHP LinkComponentHelper
 * @author kremy
 */
class LinkHelper extends Helper
{
    public $helpers = ["Html", "Form", "ViewsLimit", 'GoogleCaptcha', 'TimeLimit', 'DateLimit', 'GhostyficationAlert'];

    /**
     *
     * @var Array
     */
    static private $componentHelpers = ["ViewsLimit", 'GoogleCaptcha', 'TimeLimit', 'DateLimit', 'GhostyficationAlert'];

    /**
     * Label of the helper
     * @var string
     */
    private $label;

    /**
     * Description of the component explaining what it does
     * @var string
     */
    private $description;

    /**
     * One or several icon css class (separated by space)
     * @var string
     */
    private $icon;

    /**
     * Category of the component
     * @var string
     */
    private $category;

    /**
     * String to generate a summary of the link instance
     * @var string
     */
    private $summaryTemplate;

    /**
     * The related field name the component is targeting on
     * @var string
     */
    private $relatedField;

    /**
     * Content to display
     * @var type
     */
    private $content;

    /**
     * Name of the component
     * @var type
     */
    private $componentName;

    public static function dummy()
    {
        return new Link();
    }

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $config);
        $match = null;
        preg_match('/(\w+)Helper$/', get_class($this), $match);
        $this->description = isset($config["description"])?$config["description"]:null;
        $this->icon = isset($config["icon"])?$config["icon"]:null;
        $this->category = isset($config["type"])?$config["type"]:null;
        $this->summaryTemplate = isset($config["summaryTemplate"])?$config["summaryTemplate"]:null;
        $this->relatedField = isset($config['relatedField'])?$config["relatedField"]:null;
        $this->content = isset($config['content'])?$config["content"]:'';
        $this->label = isset($config['label'])?$config["label"]:'';
        $this->componentName = $match[1];
    }

    /**
     * Display all components fields necessary for the given link
     * @param Link $link the link entity
     * @return The
     */
    public function fields(Link $link = null, array $user = null)
    {
        $html = '';
        if (!$link) {
            return $html;
        }
        $components = $link->getComponents();
        foreach ($components as $comp) {
            $html .= $this->Html->tag(
                'div',
                $this->{$comp}->field($link, $user),
                ['class' => 'link-component-field']
            );
        }
        return $html;
    }

    /**
     * Display a component of the given link
     * @param Link $link the link entity
     * @param User $user the connected user
     * @param string $valueAsContent
     */
    public function component(Link $link = null, array $user = null, $valueAsContent = false)
    {
        $content =  $this->Html->tag(
            "span",
            '',
            ['class' => $this->icon]
        ) . $this->Html->tag(
            "span",
            (!$valueAsContent) ? $this->label : $this->getValue($link),
            ['class' => "glyphicon component-description"]
        ) . $this->Html->tag(
            "span",
            '',
            ['class' => "glyphicon glyphicon-info-sign",
            'title' => $this->description]
        );
        preg_match('/(\w+)Helper$/', get_class($this), $match);
        return $this->Html->tag(
            "li",
            $content,
            ['class' => 'component-badge',
             'data-type' => $this->category,
             'data-related-field' => htmlspecialchars($this->relatedField),
             'data-summary-template' => $this->summaryTemplate,
             'data-field-html' => htmlspecialchars($this->Html->tag("div", $this->field($link, $user), ['class' => 'link-component-field'])),
             'data-component-name' => $match[1],
             'escape' => false]
        );
    }

    public function components(Link $link = null, array $user = null, $valueAsContent = false)
    {
        $html = '';
        if ($link) {
            $colection = $link->getComponents();
        } else {
            $colection = $this::$componentHelpers;
        }
        foreach ($colection as $helper) {
            if ($this->{$helper}->isAllowed($user)) {
                $html .= $this->{$helper}->component($link, $user, $valueAsContent);
            }
        }
        if ($html == '') {
            $html = $this->Html->tag(
                'li',
                "Click on an available component to choose it",
                ["class" => "legend"]
            );
        }
        return $html;
    }

    public function isAllowed(array $user = null)
    {
        return true;
    }
}
