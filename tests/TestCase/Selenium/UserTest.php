<?php

/**
 * @group Functional
 * @group User
 */
class UsersTest extends FunctionalTest {

    public function testSignup() {
        // Check that basic element are present
        $this->open("/logout");
        $this->open("/");
        $this->verifyTextPresent("Sign up");
        $this->assertTrue($this->isElementPresent("css=a[href=\"/signup\"]"));
        $this->click("css=a[href=\"/signup\"]");
        $this->waitForPageToLoad("30000");
        $this->verifyTextPresent("Create my account");
        $this->submit("css=form[action=\"/signup\"]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("2", $this->getCssCount("css=input+div.alert-danger"));
        // #A bad value in email is detected
        $this->type("css=form[action=\"/signup\"] input[name=\"email\"]", "bademail");
        $this->submit("css=form[action=\"/signup\"]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("3", $this->getCssCount("css=input+div.alert-danger"));
        $this->type("css=form[action=\"/signup\"] input[name=\"email\"]", "email@email.fr");
        $this->type("css=form[action=\"/signup\"] input[name=\"username\"]", "username");
        $this->type("css=form[action=\"/signup\"] input[name=\"password\"]", "userpwd");
        $this->submit("css=form[action=\"/signup\"]");
        $this->waitForPageToLoad("30000");
        // #User has been logged in
        $this->assertTrue($this->isElementPresent("css=a[href=\"/logout\"]"));
    }

}

?>