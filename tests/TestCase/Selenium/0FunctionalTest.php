<?php
/**
 * @group Functional
 */

require_once 'Utils/EmailChecker.php';
require_once 'Utils/DOMChecker.php';
require_once 'Utils/UserHelper.php';
require_once 'Utils/LinkHelper.php';

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
    
    /**
     *
     * @var UserHelper
     */
    protected $userHelper;
    
    /**
     *
     * @var LinkHelper
     */
    protected $linkHelper;
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/var/www/html/ghostylink_failures/';
    protected $screenshotUrl = 'http://localhost/ghostylink_failures/';

    public function setUp() {        
        //parent::setUp();     
        //$this->shareSession(true);        
        $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->load($this);

        $this->emailChecker = new EmailChecker($this);
        $this->domChecker = new DOMChecker($this);        
        $this->userHelper = new UserHelper($this);
        $this->linkHelper = new LinkHelper($this);        
        $this->setBrowser("firefox");
        $this->setBrowserUrl("http://localhost:8765/");
        $this->prepareSession();
        $this->url('/logout');
    }

    public function waitForPageToLoad($timeout = 10000)
    {
        $this->timeouts()->implicitWait($timeout);
    }

    protected function tearDownClass()
    {        
        //parent::tearDown();
        //$this->fixtureManager->unload($this);
        
    }
    
    
    public function getDomChecker()
    {
        return $this->domChecker;
    }
}
