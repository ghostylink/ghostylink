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
    $this->open("/ghostylink/a1d0c6e83f027327d8461063f4ac58a6");
    // Check the link itself is displayed
    $this->chooseCancelOnNextConfirmation();
    $this->verifyTextPresent("Lorem ipsum dolor sit amet");
    $this->verifyTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
    $this->assertTrue($this->isElementPresent("css=a.delete-link"));
    $this->click("css=a.delete-link");
    $this->assertTrue((bool)preg_match("/^Are you sure you want to delete : 'Lorem ipsum dolor sit amet' [\s\S]$/",$this->getConfirmation()));
    // Check links statistics are displayed
    $this->assertTrue($this->isElementPresent("css=.link-stats"));
    $this->assertTrue((bool)preg_match('/^Ghostified at [\s\S]*$/',$this->getText("css=meter.link-life-percentage")));
    $this->assertTrue((bool)preg_match('/^0 views left[\s\S]*$/',$this->getText("css=meter.link-remaining-views+div")));
  }
  
  public function testAdd() {   
       // Check that basic element are present
    $this->open("/ghostylink/");
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
    $this->click("css=ul#link-components-available li#[data-related-field=max_views]");
    $this->type("css=input[name=max_views]", "42");
    $this->click("css=[type=submit]");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=section.generated-link")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    // Check we have the link
    $this->assertTrue($this->isElementPresent("css=section.generated-link"));
    // Click on the select button
    $this->click("css=button.link-copy");
    $this->assertTrue($this->isTextPresent("Press Ctrl-C"));
  }
  
  public function testAddComponents() {
    $this->open("/ghostylink/");
    $this->assertTrue($this->isTextPresent("Drop some components here"));
    // Checks the components is moved when we click on it
    $this->click("css=ul#link-components-available li[data-related-field=max_views]");
    $this->assertFalse($this->isTextPresent("Drop some components here"));
    $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
    $this->assertFalse($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
    // Checks the field + the flag have been added
    $this->assertTrue($this->isElementPresent("css=fieldset input[name=max_views]"));
    $this->assertTrue($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
    // Checks the components is removed when we click on it
    $this->click("css=ul#link-components-chosen li[data-related-field=max_views]");
    $this->assertTrue($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
    $this->assertFalse($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
    // Checks the field + flage have removed from the form
    $this->assertFalse($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
    $this->assertFalse($this->isElementPresent("css=fieldset input[name=max_views]"));
    // ###################################
    // Check  the component iteraction is still here when errors are retrieved
    // ###################################
    $this->open("/ghostylink/");
    $this->type("css=input[name=title]", "Myawesome title");
    $this->type("css=textarea[name=content]", "My awesome private content");
    $this->click("css=ul#link-components-available li[data-related-field=max_views]");
    $this->click("css=form [type=submit]");
    // Checks the components is removed when we click on it
    $this->click("css=ul#link-components-chosen li[data-related-field=max_views]");
    $this->assertTrue($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
    $this->assertFalse($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
    $this->assertFalse($this->isElementPresent("form div.error"));
    $this->assertFalse($this->isElementPresent("css=fieldset input[name=max_views]"));
    $this->assertFalse($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
    // Checks the components is moved when we click on it
    $this->click("css=ul#link-components-available li[data-related-field=max_views]");
    $this->assertFalse($this->isTextPresent("Drop some components here"));
    $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
    $this->assertFalse($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
    $this->assertTrue($this->isElementPresent("css=fieldset input[name=max_views]"));
    $this->assertTrue($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
  }
  
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>