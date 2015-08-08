<?php

/**
 * @group Functional
 */
class rootTest extends PHPUnit_Extensions_SeleniumTestCase {

    public $fixtureManager = null;
    public $autoFixtures = true;
    public $dropTables = true;
    public $fixtures = [
        'Links' => 'app.links'
    ];
    protected $screenshotPath = '/var/www/html/ghostylink_failures/root';
    protected $screenshotUrl = 'http://localhost/html/ghostylink_failures/root';

    protected function setUp() {
        parent::setUp();
        parent::shareSession(true);
        $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
        $this->fixtureManager->fixturize($this);
        $this->fixtureManager->load($this);
        $this->setBrowser("*firefox");
        $this->setHost('http://jenkins.ghostylink.org');
        $this->setBrowserUrl("http://localhost:8765/");
    }

    public function testFormIsPresent() {
        $this->open("/");
        $this->assertTrue($this->isElementPresent("css=form[action=\"/add\"]"));
        $this->assertEquals("1", $this->getCssCount("form[action=\"/add\"] input[type=\"text\"]"));
        $this->assertEquals("1", $this->getCssCount("form[action=\"/add\"] textarea"));
    }

    protected function tearDown() {
        parent::tearDown();
        $this->fixtureManager->unload($this);
        //$this->fixtureManager->shutDown();
    }

}

?>