<?php
/**
 * @group Functional
 */
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
    protected $captureScreenshotOnFailure = TRUE;
    protected $screenshotPath = '/var/www/html/ghostylink_failures/';
    protected $screenshotUrl = 'http://localhost/ghostylink_failures/';

    protected function setUp() {
        parent::setUp();
        parent::shareSession(true);        
        $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->load($this);
        $this->setBrowser("firefox");

        if (getenv('CIS_SERVER') == '1') {
            $this->setHost('jenkins.ghostylink.org');
            $this->screenshotPath = '/var/www/ghostylink/selenium_failures';
            $this->screenshotUrl = 'http://selenium.ghostylink.org';
        }
        $this->setBrowserUrl("http://localhost:8765/");

    }
    
    /**
     * Assert a text is present in the current page
     * @param <string> $text text to find in page
     * @param <string> $prefixMsg A prefix message to add in case of failure
     */
    public function assertTextPresent($text, $prefixMsg = "")
    {
        $source = $this->source();
        $this->assertContains($text, $source, "$prefixMsg Text '$text' is present in page");
    }
    
    /**
     * Assert a text is not present in the current page
     * @param <string> $text text that should not be present
     * @param <string> $prefixMsg A prefix message to add in case of failure
     */
    public function assertTextNotPresent($text, $prefixMsg = "")
    {
        $source = $this->source();
        $this->assertNotContains($text, $source, "$prefixMsg Text '$text' is not present in page");
    }
    
    /**
     * Assert at least one element is present in the page.
     * @param <string> $local localisation string. Start css=expr or id=expr
     */
    public function assertElementPresent($local)
    {
        $endSelector = strpos($local, "=");
        $selectorPart = explode("=", $local);

        if ($selectorPart[0] == "css") {
            $found = $this->byCssSelector($selectorPart[1]);
        } elseif ($selectorPart[0] == "id") {
            $found = $this->byId($selectorPart[1]);
        } else {
            $this->assertFalse("Element $selectorPart[0] not yet implemented");
        }

        $this->assertGreaterThan(
            0,
            count($found),
            "At least one element matching $local is found"
        );
    }

    protected function tearDown()
    {
        parent::tearDown();
        //$this->fixtureManager->unload($this);
        $this->url('/logout');
    }
}
