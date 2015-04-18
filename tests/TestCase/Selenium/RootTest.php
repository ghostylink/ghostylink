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
    $this->setBrowserUrl("http://kevin-remy.fr/");
  }

  public function testFormIsPresent()
  {
    $this->open("/ghostylink/");
    $this->verifyTextPresent("Cache me if you can", 'slogan is here');   
    $this->assertTrue($this->isElementPresent("css=form[action=\"/ghostylink/links/add\"]"), 'Add link form is here');
    $this->assertEquals("2", 
                        $this->getCssCount("form[action=\"/ghostylink/links/add\"] input[type=\"text\"]"),
                        'Two fields are in the link add form');
  }
  
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>