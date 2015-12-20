<?php
/**
 * @group Functional
 * @group User
 */
class UserModifyTest extends FunctionalTest
{
    /**
     * @group Develop
     */
    public function testEmailSent()
    {
        /*********** Clear maildev inbox *************/
        $this->open("http://localhost:1080/#/");
        $this->click('css=.toolbar a[ng-click="deleteAll()"]');

        /*********  Log in the user *******************/
        $this->open("/logout");
        $this->type("id=username", "user1");
        $this->type("id=password", "user1user1");
        $this->clickAndWait("css=button.btn.btn-default");
        $this->click("link=Welcome user1");
        $this->clickAndWait("link=My information");
        $this->assertTrue($this->isTextPresent("Modify my information"), "Information page is displayed");

        /**************************************************/
        /**************** Checks **************************/
        /**************************************************/
        // Check an information which is not the email does not implies email sending
        $this->type("css=input[name=username]", "usernamechanged");
        $this->open("http://localhost:1080/");
        $this->assertTextNotPresent("user1@ghostylink.org", "Email sending has been triggered");

        $this->open("/me/edit");
        $this->type("id=email", "anemailchanged@test.fr");
        $this->submitAndWait("css=form");

        $this->open("http://localhost:1080/");
        $this->assertTextPresent("anemailchanged@test.fr", "Email sending has been triggered");

    }
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
    $this->assertTextPresent("Delete my account");
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