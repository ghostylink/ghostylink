<?php
class basicTest extends PHPUnit_Extensions_SeleniumTestCase
{
  protected function setUp()
  {
    parent::setUp();
    $this->setBrowser("*firefox");
    $this->setBrowserUrl("http://localhost/");
  }

  public function testMyTestCase()
  {
    $this->open("/ghostylink/");
    $this->assertTrue($this->isTextPresent("Ghostylink"));
    $this->assertTrue($this->isElementPresent("css=div#main-content"));
  }
}
?>