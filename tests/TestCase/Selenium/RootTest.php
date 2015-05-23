<?php
/**
 * @group Functional 
 */
class rootTest extends PHPUnit_Extensions_SeleniumTestCase
{
    public $fixtureManager = null ;  
  public $autoFixtures = true ;
  public $dropTables = true;
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

  public function testFormIsPresent()
  {
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