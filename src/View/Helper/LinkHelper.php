<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\View\Helper;

use App\Model\Entity\Link;
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

    public function __construct(View $view, array $config = array())
    {
        parent::__construct($view, $config);
        $this->description = isset($config["description"])?$config["description"]:null;
        $this->icon = isset($config["icon"])?$config["icon"]:null;
        $this->category = isset($config["type"])?$config["type"]:null;
        $this->summaryTemplate = isset($config["summaryTemplate"])?$config["summaryTemplate"]:null;
        $this->relatedField = isset($config['relatedField'])?$config["relatedField"]:null;
        $this->content = isset($config['content'])?$config["content"]:'';
        $this->label = isset($config['label'])?$config["label"]:'';
    }

    /**
     * Display the component badge for the given link
     * @param Link $link
     * @param string $content content of the link;
     * @return string The generated html
     */
    public function badges(Link $link = null, $content = '')
    {
        $html = '';
        if (!$link) {
            return $html;
        }
        $components = $link->getComponents();
        foreach ($components as $comp) {
            $html .= $this->{$comp}->component($link, $this->content);
        }
        return $html;
    }

    /**
     * Display all components fields necessary for the given link
     * @param Link $link the link entity
     * @return The
     */
    public function allFields(Link $link = null)
    {
        $html = '';
        if (!$link) {
            return $html;
        }
        $components = $link->getComponents();
        foreach ($components as $comp) {
            $html .= $this->{$comp}->field($link);
        }
        return $html;
    }

    /**
     * Display a component of the given link
     * @param Link $link the link entity
     * @param string $content
     */
    public function component(Link $link, $content = '')
    {
        return $this->Html->tag(
            "li",
            $content,
            ['class' => $this->icon,
             'data-type' => $this->category,
             'data-related-field' => htmlspecialchars($this->relatedField),
             'data-summary-template' => $this->summaryTemplate,
             'escape' => false]
        );
    }

    public function badge($content = null)
    {

        $content =  $this->Html->tag(
            "span",
            '',
            ['class' => $this->icon]
        ) . $this->Html->tag(
            "span",
            (!$content) ? $this->label : $content,
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
             'data-related-field' => $this->relatedField,
             'data-summary-template' => $this->summaryTemplate,
             'data-field-html' => htmlspecialchars($this->field()),
             'data-component-name' => $match[1],
             'escape' => false]
        );
    }

    public function components($user = null)
    {
        $html = '';
        foreach ($this::$componentHelpers as $helper) {
            if ($this->{$helper}->isAllowed($user)) {
                $html .= $this->{$helper}->badge();
            }
        }
        return $html;
    }

    public function isAllowed($user = null)
    {
        return true;
    }
}
