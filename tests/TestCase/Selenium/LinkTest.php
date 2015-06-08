<?php
/**
 * @group Functional
 * @group Link 
 */
class LinksTest extends PHPUnit_Extensions_SeleniumTestCase
{
  public $fixtureManager = null ;  
  public $autoFixtures = true ;
  public $dropTables = true;
  
  protected $captureScreenshotOnFailure = true;
  protected $screenshotPath = '/var/www/html/ghostylink_failures/links';  
  protected $screenshotUrl = 'http://localhost/html/ghostylink_failures/links';    
  
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
    $this->setBrowserUrl("http://localhost:8765/");
  }

  public function testView()
    {      
    $this->open("/a1d0c6e83f027327d8461063f4ac58a6");
    // Check the link itself is displayed
    // It has a max_views, check the information is not yet present
    $this->assertFalse($this->isTextPresent("Lorem ipsum dolor sit amet"));
    $this->assertFalse($this->isTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat."));
    $this->click("css=button#load-link");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("Lorem ipsum dolor sit amet")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->assertTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
    $this->assertTextPresent("Lorem ipsum dolor sit amet");
    
    $this->chooseCancelOnNextConfirmation();
    $this->assertTrue($this->isElementPresent("css=a.delete-link"));
    
    $this->click("css=a.delete-link");
    $this->assertTrue((bool)preg_match('/^Are you sure you want to delete : \'Lorem ipsum dolor sit amet\' [\s\S]$/',$this->getConfirmation()));
    
    // Check links statistics are displayed
    $this->assertTrue($this->isElementPresent("css=.link-stats"));
    $this->assertTrue((bool)preg_match('/^Ghostified at [\s\S]*$/',$this->getText("css=meter.link-life-percentage")));
    $this->assertTrue((bool)preg_match('/^0 views left[\s\S]*$/',$this->getText("css=meter.link-remaining-views+div")));    
    // No max_views, check the information is displayed in 1 step    
    $this->open("/f27d846103f4ac6c6e8358a6a1d00273");    
    $this->assertFalse($this->isTextPresent("NotFound"));
    $this->assertFalse($this->isElementPresent("css=section.unloaded button"));
    $this->assertFalse($this->isElementPresent("css=section.unloaded img"));
  }
  
  public function testAdd() {   
       // Check that basic element are present
    $this->open("/");
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
  
  public function testAddComponentsWithSubmit() {
        // ###################################
    // Check  the component iteraction is still here when errors are retrieved
    // ###################################
    // When the error is not on a component field
    $this->open("/");
    $this->type("css=input[name=title]", "Myawesome title");
    $this->click("css=ul#link-components-available li[data-related-field=max_views]");
    $this->type("css=input[name=max_views]", "2");
    $this->click("css=form [type=submit]");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=div.error textarea")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
    $this->assertTrue($this->isElementPresent("css=input[name=max_views]"));
    $this->type("css=input[name=title]", "");
    $this->type("css=textarea[name=content]", "Myawesome contenet");
    $this->click("css=form [type=submit]");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=div.error input[name=title]")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
    $this->assertTrue($this->isElementPresent("css=input[name=max_views]"));
    // When the error is on a component field
    $this->open("/");
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
  
  public function testAddComponentsNoSubmit() {
    $this->open("/");
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
  }
  
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>