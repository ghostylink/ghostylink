<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserRegisterTest
 *
 * @author kremy
 * @group User
 * @group Functional
 */
class UserRegisterTest extends FunctionalTest {
    
    public function testSignupNoMailSuccess()
    {
        $this->url("/signup");
        $userName = 'SignupNoMailSuccess';
        $this->domChecker->fillElements([
           'css=form[action="/signup"] input[name="username"]' => "SignupNoMailSuccess",
           'css=form[action="/signup"] input[name="password"]' => 'SignupNoMailSuccess',
           'css=form[action="/signup"] input[name="confirm_password"]' => 'SignupNoMailSuccess',
        ]);
        $submitButton = 'css=form[action="/signup"] button[type="submit"]';
        $this->domChecker->clickOnElementMatching($submitButton);
        $this->domChecker->assertTextPresent("SignupNoMailSuccess");
    }
    
    public function testSignupMailSuccess()
    {
        $this->url("/signup");
        
        $this->domChecker->fillElements([
           'css=form[action="/signup"] input[name="username"]' => "SignupMailSuccess",
           'css=form[action="/signup"] input[name="password"]' => 'SignupMailSuccess',
           'css=form[action="/signup"] input[name="email"]' => 'signup.mailsuccess@mail.fr',
           'css=form[action="/signup"] input[name="confirm_password"]' => 'SignupMailSuccess',
        ]);
        $submitButton = 'css=form[action="/signup"] button[type="submit"]';
        $this->domChecker->clickOnElementMatching($submitButton);
        $this->domChecker->assertTextPresent("SignupMailSuccess");
        $this->emailChecker->assertMailReceived(
            "signup.mailsuccess@mail.fr",
            "Ghostylink - Email verification"
        );
    }
    
    public function testSignupFailServer()
    {
        $this->url("/signup");
        $this->domChecker->removeHTML5Validation('form[action="/signup"]');
        $submitButton = 'css=form[action="/signup"] button[type="submit"]';
        $errors = "css=form .alert.alert-danger";
        $this->domChecker->fillElements([
           'css=form[action="/signup"] input[name="email"]' => "badEmail"
        ]);
        $this->domChecker->clickOnElementMatching($submitButton);
        $this->waitForPageToLoad();
        $this->domChecker->removeHTML5Validation('form[action="/signup"]');
        $this->domChecker->assertElementsCount($errors, 4);
        
        $this->domChecker->fillElements([
           'css=form[action="/signup"] input[name="email"]' => "blabal@bllba.fr",
           'css=form[action="/signup"] input[name="username"]' => "username"
        ]);
        $this->domChecker->clickOnElementMatching($submitButton);
        $this->waitForPageToLoad();
        $this->domChecker->removeHTML5Validation('form[action="/signup"]');
        $this->domChecker->assertElementsCount($errors, 2);
        $this->domChecker->fillElements([
           'css=form[action="/signup"] input[name="password"]' => 'password'
        ]);
        $this->domChecker->clickOnElementMatching($submitButton);
        $this->domChecker->removeHTML5Validation('form[action="/signup"]');

        $this->waitForPageToLoad();
        $this->domChecker->assertElementsCount($errors, 1);
        $this->domChecker->fillElements([
           'css=form[action="/signup"] input[name="confirm_password"]' => 'password'
            
        ]);
        $this->domChecker->clickOnElementMatching($submitButton);
        $this->domChecker->assertElementsCount($errors, 0);
    }
    /*public function testSignup() {
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
        // Email + password + confirm are in error
        $this->assertEquals("3", $this->getCssCount("css=input+div.alert-danger"));
        // #A bad value in email is detected
        $this->type("css=form[action=\"/signup\"] input[name=\"email\"]", "bademail");
        $this->submit("css=form[action=\"/signup\"]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("4", $this->getCssCount("css=input+div.alert-danger"));
        $this->type("css=form[action=\"/signup\"] input[name=\"email\"]", "email@email.fr");
        $this->type("css=form[action=\"/signup\"] input[name=\"username\"]", "username");
        $this->type("css=form[action=\"/signup\"] input[name=\"password\"]", "userpwd");
        $this->type("css=form[action=\"/signup\"] input[name=\"confirm_password\"]", "userpwd");
        $this->submit("css=form[action=\"/signup\"]");
        $this->waitForPageToLoad("30000");
        // #User has been logged in
        $this->assertTrue($this->isElementPresent("css=a[href=\"/logout\"]"));
        $this->assertElementNotPresent('css=[data-related-field="ghostification_alert"]');
    }*/
}
