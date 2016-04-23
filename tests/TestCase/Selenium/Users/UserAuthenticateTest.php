<?php

/**
 * @group Functional
 * @group User
 */
class UserLoginTest extends FunctionalTest
{
  public function testMyTestCase()
  {
    $this->open("/logout");
    // ## Check bad authentication
    $this->open("/");
    $this->type("id=username", "badUser");
    $this->type("id=password", "badUser");
    $this->click("css=button.btn.btn-default");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Username or password not valid"));
    // ## Check good authentication
    $this->open("/");
    $this->type("id=username", "user1");
    $this->type("id=password", "user1user1");
    $this->click("css=button.btn.btn-default");
    $this->waitForPageToLoad("30000");
    $this->assertFalse($this->isTextPresent("Username or password not valid"));
    $this->assertTrue($this->isElementPresent("css=a[href=\"/logout\"]"));
  }
}
?>