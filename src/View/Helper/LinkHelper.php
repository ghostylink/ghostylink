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
        $this->category = isset($config["category"])?$config["category"]:null;
        $this->summaryTemplate = isset($config["summaryTemplate"])?$config["summaryTemplate"]:null;
        $this->relatedField = isset($config['relatedField'])?$config["relatedField"]:null;
        $this->content = isset($config['content'])?$config["content"]:'';
    }

    /**
     * Display the component badge for the given link
     * @param Link $link
     * @param string $content content of the link;
     */
    public function displayBadges(Link $link = null, $content = '')
    {
        if (!$link) {
            return;
        }
        $components = $link->getComponents();
        foreach ($components as $comp) {
            $this->{$comp}->displayComponent($link, $this->content);
        }
    }

    /**
     * Display all components fields necessary for the given link
     * @param Link $link the link entity
     * @return void
     */
    public function displayAllFields(Link $link = null)
    {
        if (!$link) {
            return;
        }
        $components = $link->getComponents();
        foreach ($components as $comp) {
            $this->{$comp}->displayField($link);
        }
    }

    /**
     * Disaplay a component of the given link
     * @param Link $link the link entity
     * @param string $content
     */
    public function displayComponent(Link $link, $content = '')
    {
        echo $this->Html->tag(
            "li",
            $content,
            ['class' => $this->icon,
             'data-type' => $this->category,
             'data-related-field' => htmlspecialchars($this->relatedField),
             'data-summary-template' => $this->summaryTemplate,
             'escape' => false]
        );
    }
}
