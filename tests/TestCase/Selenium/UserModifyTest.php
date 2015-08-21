<?php
/**
 * @group Functional
 * @group User
 */
class UserModifyTest extends FunctionalTest
{

  public function testMyTestCase()
  {
    $this->open("/logout");
    $this->type("id=username", "user1");
    $this->type("id=password", "user1user1");
    $this->click("css=button.btn.btn-default");
    $this->waitForPageToLoad("30000");
    $this->click("link=Welcome user1");
    $this->click("link=My information");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isTextPresent("Modify my information"));
    $this->assertTrue($this->isElementPresent("css=input[name=username]"));
    $this->assertTrue($this->isElementPresent("css=input[name=email]"));
    $this->type("css=input[name=username]", "user2");
    $this->type("css=input[name=email]", "user2@domain");
    $this->submit("css=form");
    $this->waitForPageToLoad("30000");
    $this->assertTrue($this->isElementPresent("css=form .error"));
    $this->type("css=input[name=email]", "user2@domain.fr");
    $this->submit("css=form");
    $this->waitForPageToLoad("30000");
    $this->assertEquals("Welcome user2", $this->getText("css=nav li.dropdown a"));
    $this->click("link=Welcome user2");
    $this->click("link=My information");
    $this->waitForPageToLoad("30000");
    $this->type("css=input[name=username]", "user1");
    $this->submit("css=form");
    $this->waitForPageToLoad("30000");
  }
}
?>