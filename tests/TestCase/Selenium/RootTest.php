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
    $this->open("ghostylink/");
    $this->verifyTextPresent("Cache me if you can", 'slogan is here');   
    $this->assertTrue($this->isElementPresent("css=form[action=\"/ghostylink/links/add\"]"), 'Add link form is here');
    $this->assertEquals("1", 
                        $this->getCssCount("form[action=\"/ghostylink/links/add\"] input[type=\"text\"]"),
                        'One input is in the link add form');
    $this->assertEquals("1", 
                        $this->getCssCount("form[action=\"/ghostylink/links/add\"] textarea"),
                        'One textarea is in the link add form');
  }
  
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>