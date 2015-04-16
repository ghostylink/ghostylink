<?php
class LinksTest extends PHPUnit_Extensions_SeleniumTestCase
{
  public $fixtureManager = null ;  
  public $autoFixtures = true ;
  public $dropTables = true;
  public $fixtures = [
        'Links' => 'app.links'
  ];
  protected function setUp()
  {
    $this->fixtureManager = new Cake\TestSuite\Fixture\FixtureManager();
    $this->fixtureManager->fixturize($this);
    $this->fixtureManager->load($this);
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://localhost/");
  }

  public function testView()
  {
    $this->open("/ghostylink/links/view/1");
    $this->chooseCancelOnNextConfirmation();
    $this->verifyTextPresent("Lorem ipsum dolor sit amet");
    $this->verifyTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
    $this->assertTrue($this->isElementPresent("css=a.delete-link"));
    $this->click("css=a.delete-link");
    $this->assertTrue((bool)preg_match('/^Are you sure you want to delete # 1[\s\S]$/',$this->getConfirmation()));
  }
  
  public function testAdd() {
    $this->open("/ghostylink/links/add");
    $this->assertTrue($this->isElementPresent("css=form"));
    $this->assertTrue($this->isElementPresent("css=input[type=text]"));
    $this->assertTrue($this->isElementPresent("css=input[type=text]"));
  }
  
  
  protected function tearDown() {
    parent::setUp();
    $this->fixtureManager->unload($this);
    //$this->fixtureManager->shutDown();
  }
}
?>