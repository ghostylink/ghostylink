<?php
class basicTest extends PHPUnit_Extensions_SeleniumTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Links' => 'app.links'
    ];
    
  public $fixtureManager = null ;  
  public $autoFixtures = true ;
  public $dropTables = true;
  protected function setUp()
  {
    parent::setUp();
    $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
    $this->fixtureManager->fixturize($this);
    $this->fixtureManager->load($this);
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://kevin-remy.fr/");
//    $this->loadFixtures('app.Links');
  }

  public function testMyTestCase()
  {
    $this->open("/ghostylink/");
    $this->assertTrue($this->isTextPresent("Ghostylink"));
    $this->assertTrue($this->isElementPresent("css=div#main-content"));
    $this->open('/ghostylink/links/view/1');     
    $this->assertFalse($this->isTextPresent('was not found on this server'), 
                      'The page is not a 404');
    $this->assertFalse($this->isTextPresent('Record not found'));
  }
  protected function tearDown() {
    parent::tearDown();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}

?>