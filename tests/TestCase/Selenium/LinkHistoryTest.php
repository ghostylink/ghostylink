<?php
/**
 * @group Functional
 * @group Link
 */
class LinkHistoryTest extends FunctionalTest
{

  public function testMyTestCase()
  {
    $this->open("/logout");
    $this->type("id=username", "userwithnolink");
    $this->type("id=password", "userwithnolink");
    $this->click("css=button.btn.btn-default");
    $this->waitForPageToLoad("30000");
    $this->click("link=Welcome userwithnolink");
    $this->click("link=My links");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isElementPresent("css=tr td"));
    $this->open("/logout");
    $this->type("id=username", "user1");
    $this->type("id=password", "user1user1");
    $this->click("css=button.btn.btn-default");
    $this->waitForPageToLoad("30000");
    $this->click("link=Welcome user1");
    $this->click("link=My links");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("css=tr td"));
    $this->assertTrue($this->isTextPresent("User 1 id 10"));
    $this->click("link=Title");
    $this->waitForPageToLoad("30000");
    $this->click("link=Title");
    $this->waitForPageToLoad("30000");
    // ### id 10 is on page 2
    $this->assertFalse($this->isTextPresent("User 1 id 10"));
    $this->click("link=2");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("User 1 id 10"));
    $this->assertTrue($this->isTextPresent("2 of 2"));
  }
}
?>