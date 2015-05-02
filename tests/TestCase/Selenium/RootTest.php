<?php
class rootTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
     parent::setUp();
    $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
    $this->fixtureManager->fixturize($this);
    $this->fixtureManager->load($this);
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://localhost/");
  }

  public function testFormIsPresent()
  {
    $this->open("/ghostylink/");    
    $this->assertTrue($this->isElementPresent("css=form[action=\"/ghostylink/add\"]"));
    $this->assertEquals("1", $this->getCssCount("form[action=\"/ghostylink/add\"] input[type=\"text\"]"));
    $this->assertEquals("1", $this->getCssCount("form[action=\"/ghostylink/add\"] textarea"));
  }
  
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>