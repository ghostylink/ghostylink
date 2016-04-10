<?php
/**
 * @group Functional
 */

require_once 'Utils/EmailChecker.php';
require_once 'Utils/DOMChecker.php';

class FunctionalTest extends PHPUnit_Extensions_Selenium2TestCase  {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Links' => 'app.links',
        'Users' => 'app.users',
        'AlertParameters' => 'app.alert_parameters'
    ];
    public $fixtureManager = null;
    public $autoFixtures = true;
    public $dropTables = true;
    
    /**
     *
     * @var EmailChecker
     */
    protected $emailChecker;
    
    /**
     *
     * @var DOMChecker
     */
    protected $domChecker;
    
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/var/www/html/ghostylink_failures/';
    protected $screenshotUrl = 'http://localhost/ghostylink_failures/';

    protected function setUp() {
        parent::setUp();
        parent::shareSession(true);        
        $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->load($this);

        $this->emailChecker = new EmailChecker($this);
        
        $this->domChecker = new DOMChecker($this);

        $this->setBrowser("firefox");

        if (getenv('CIS_SERVER') == '1') {
            $this->setHost('jenkins.ghostylink.org');
            $this->screenshotPath = '/var/www/ghostylink/selenium_failures';
            $this->screenshotUrl = 'http://selenium.ghostylink.org';
        }
        $this->setBrowserUrl("http://localhost:8765/");
        
    }

    protected function waitForPageToLoad($timeout)
    {
        $this->timeouts()->implicitWait($timeout);
    }

    protected function tearDown()
    {
        parent::tearDown();
        //$this->fixtureManager->unload($this);
        $this->url('/logout');
    }
    
    public function getDomChecker()
    {
        return $this->domChecker;
    }
}
