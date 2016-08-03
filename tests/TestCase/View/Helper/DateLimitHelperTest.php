<?php

namespace App\Test\TestCase\View\Helper;

use Cake\View\View;
use App\View\Helper\DateLimitHelper;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertParametersTable Test Case
 */
class DateLimitHelperTest extends TestCase
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
        $this->helper = new DateLimitHelper($View);
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $this->link = TableRegistry::get('Links', $config)->findById(4)->first();
    }

    public function testField()
    {
        $html = $this->helper->field($this->link);
        $this->assertRegExp('/name="death_date"/', $html);
    }

    public function testComponent()
    {
        $html = $this->helper->component($this->link);
        $this->assertRegExp("/glyphicon-calendar\"/", $html);
    }

    public function testGetValue()
    {
        $text = $this->helper->getValue($this->link);
        $this->assertEquals("11/10/55, 6:38 AM", $text);
    }
}
