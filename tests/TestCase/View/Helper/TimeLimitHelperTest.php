<?php

namespace App\Test\TestCase\View\Helper;

use Cake\View\View;
use App\View\Helper\TimeLimitHelper;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertParametersTable Test Case
 */
class TimeLimitHelperTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.links'
    ];

    public $helper = null;

    // Here we instantiate our helper
    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->helper = new TimeLimitHelper($View);
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $this->link = TableRegistry::get('Links', $config)->findById(21)->first();
    }

    public function testField()
    {
        $html = $this->helper->field($this->link);
        $this->assertRegExp('/name="death_time"/', $html);
    }

    public function testComponent()
    {
        $html = $this->helper->component($this->link);
        $this->assertRegExp("/glyphicon-time\"/", $html);
    }

    public function testGetValue()
    {
        $text = $this->helper->getValue($this->link);
        $this->assertEquals('1 day', $text);
        
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $link = TableRegistry::get('Links', $config)->findById(22)->first();
        $this->assertEquals('1 week', $this->helper->getValue($link));
        $link = TableRegistry::get('Links', $config)->findById(23)->first();
        $this->assertEquals('1 month', $this->helper->getValue($link));
    }
}
