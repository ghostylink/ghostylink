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

    public function setUp()
    {
        $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->load($this);
        $this->emailChecker = new EmailChecker($this);
        $this->domChecker = new DOMChecker($this);
        $this->userHelper = new UserHelper($this);
        $this->linkHelper = new LinkHelper($this);
        //$this->setBrowser("firefox");

        // Target a selenium node linked with required containers
        if (getenv("BUILD_TAG") != "") {
            $this->setDesiredCapabilities([
                "applicationName" => getenv("BUILD_TAG")
            ]);
        }
        $this->setBrowser("firefox");
        if (getenv("CI_FROM_DOCKER") == 1) {
            $this->setHost("selenium-hub");
            exec("hostname --ip-address", $output);
            $ip = $output[0];
            $this->setBrowserUrl("http://$ip/");
        } else {
            $this->setBrowserUrl("http://localhost:8765/");
        }
        $this->prepareSession();
        $this->url('/logout');
    }

    public function waitForPageToLoad($timeout = 10000)
    {
        $this->timeouts()->implicitWait($timeout);
    }
    
    public function getDomChecker()
    {
        return $this->domChecker;
    }
}
