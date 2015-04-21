<?php
class LinksTest extends PHPUnit_Extensions_SeleniumTestCase
{
  public $fixtureManager = null ;  
  public $autoFixtures = true ;
  public $dropTables = true;
  
  protected $captureScreenshotOnFailure = TRUE;
  protected $screenshotPath = '/var/www/ghostylink/selenium_screenshots/';
  protected $screenshotUrl = 'http://kevin-remy.fr/ghostylink_selenium/screenshots';    
  
  public $fixtures = [
        'Links' => 'app.links'
  ];
  protected function setUp()
  {
    parent::setUp();
    
    $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
    $this->fixtureManager->fixturize($this);
    $this->fixtureManager->load($this);
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://localhost/");
  }

  public function testView()
  {
    $this->open("ghostylink/links/view/a1d0c6e83f027327d8461063f4ac58a6");
    $this->chooseCancelOnNextConfirmation();
    $this->verifyTextPresent("Lorem ipsum dolor sit amet");
    $this->verifyTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
    $this->assertTrue($this->isElementPresent("css=a.delete-link"));
    $this->click("css=a.delete-link");
    $this->assertTrue((bool)preg_match('/^Are you sure you want to delete # 1[\s\S]$/',$this->getConfirmation()));
  }
  
  public function testAdd() {   
    // Check that basic element are present
    $this->open("/ghostylink/links/add");
    try {
        $this->assertTrue($this->isElementPresent("css=input[type=text]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("css=textarea"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    try {
        $this->assertTrue($this->isElementPresent("css=[type=submit]"));
    } catch (PHPUnit_Framework_AssertionFailedError $e) {
        array_push($this->verificationErrors, $e->toString());
    }
    // Fill up the fields
    $this->type("css=input[type=text][name=title]", "My super content");
    $this->type("css=textarea[name=content]", "My super title");
    $this->click("css=[type=submit]");
    $this->waitForPageToLoad("30000");
    // Check you are on the link view page
    $this->assertTrue($this->isElementPresent("css=article section h2"));
    // Check the shown information is what you have just inserted
    $this->assertTrue($this->isTextPresent("My super title"));
    $this->assertTrue($this->isTextPresent("My super content"));
  }
  
  
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>