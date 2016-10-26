<?php

/**
 * @group Functional
 * @group User 
 * @group Develop
 */
class UsersAuthenticateTest extends FunctionalTest {

    public function testAuthentFailed()
    {
        $this->userHelper->login("baduser", "badpassword");
        $this->domChecker->assertElementPresent("css=.alert.alert-danger");
        $alert = $this->domChecker->findElementMatching("css=.alert.alert-danger");
        $this->assertRegExp("/Username or password not valid/", $alert->text());
    }
    
    public function testAuthentSuccess()
    {
        $this->userHelper->login("user1", "user1user1");
        $this->domChecker->assertTextPresent("Welcome user1");
    }
}
