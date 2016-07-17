<?php

namespace App\Test\TestCase\View\Helper;

use Cake\View\View;
use App\View\Helper\GhostyficationAlertHelper;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertParametersTable Test Case
 */
class GhostyficationAlertHelperTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.links',
        'app.alert_parameters'
    ];

    public $helper = null;

    // Here we instantiate our helper
    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->helper = new GhostyficationAlertHelper($View);
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $this->link = TableRegistry::get('Links', $config)
                ->findById(17)
                ->contain("AlertParameters")
                ->first();
    }

    public function testField()
    {
        $html = $this->helper->field($this->link);
        $this->assertRegExp("/name=\"alert_parameter\[life_threshold\]\"/", $html);
        $this->assertRegExp("/value=\"1\"/", $html);
    }

    public function testComponent()
    {
        $html = $this->helper->component($this->link);
        $this->assertRegExp("/glyphicon-bell\"/", $html);
    }
}
