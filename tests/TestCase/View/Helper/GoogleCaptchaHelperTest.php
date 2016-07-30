<?php

namespace App\Test\TestCase\View\Helper;

use Cake\View\View;
use App\View\Helper\GoogleCaptchaHelper;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertParametersTable Test Case
 */
class GoogleCaptchaHelperTest extends TestCase
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
        $this->helper = new GoogleCaptchaHelper($View);
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $this->link = TableRegistry::get('Links', $config)->findById(16)->first();
    }

    public function testField()
    {
        $html = $this->helper->field($this->link);
        $this->assertRegExp('/name="google_captcha"/', $html);
        $this->assertRegExp('/type="hidden"/', $html);
    }

    public function testComponent()
    {
        $html = $this->helper->component($this->link);
        $this->assertRegExp("/glyphicon-ok-circle\"/", $html);
    }
}
