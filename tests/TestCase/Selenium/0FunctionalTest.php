<?php

/**
 * @group Functional
 */
class FunctionalTest extends PHPUnit_Extensions_SeleniumTestCase {

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
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/var/www/html/ghostylink_failures/';
    protected $screenshotUrl = 'http://localhost/ghostylink_failures/';

    protected function setUp() {
        parent::setUp();
        parent::shareSession(true);
        $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->load($this);
        $this->setBrowser("*firefox");

        if (getenv('CIS_SERVER') == '1') {
            $this->setHost('jenkins.ghostylink.org');
            $this->screenshotPath = '/var/www/ghostylink/selenium_failures';
            $this->screenshotUrl = 'http://selenium.ghostylink.org';
        }
        $this->setBrowserUrl("http://localhost:8765/");
    }

    protected function tearDown() {
        parent::tearDown();
        $this->open('/logout');
        $this->fixtureManager->unload($this);
    }

}

?>