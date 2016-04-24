<?php
/**
 * @group Functional
 * @group User
 */
class UserModifyTest extends FunctionalTest
{
    public function testAccess()
    {
        $this->userHelper->login("user1", "user1user1");
        $this->domChecker->clickOnElementMatching("link=Welcome user1");
        $this->domChecker->clickOnElementMatching("link=My information");
        $this->waitForPageToLoad();
        $this->domChecker->assertElementPresent('css=form[action="/me/edit"]');
    }
    public function testModifyEmail()
    {
        $this->emailChecker->clearInBox();
        $this->userHelper->login("user1", "user1user1");
        
        $this->url("/me/edit");
        
        $this->domChecker->fillElements([
           'css=[name="email"]' => 'email.changed@confirm.fr'
        ]);
        
        $this->domChecker->clickOnElementMatching('css=form[action="/me/edit"] button[type="submit"]');
        $this->waitForPageToLoad();
        $this->emailChecker->assertMailReceived(
            'email.changed@confirm.fr',
            'Ghostylink - Email verification'
        );
        
        $this->url("/me/edit");
        $this->domChecker->assertTextPresent("Not yet validated");
    }
    
    public function testModifyNotEmail()
    {
        $this->emailChecker->clearInBox();
        $this->userHelper->login("user1", "user1user1");
        
        $this->url("/me/edit");
        
        $this->domChecker->fillElements([
           'css=[name="username"]' => 'usernameNew'
        ]);
        
        $this->domChecker->clickOnElementMatching('css=form[action="/me/edit"] button[type="submit"]');
        $this->waitForPageToLoad();
        $this->emailChecker->assertMailNotReceived(
            'email.changed@confirm.fr',
            'Ghostylink - Email verification'
        );
        
        $this->url("/me/edit");
        $this->domChecker->assertTextPresent("Validated");
    }
    
    public function testModifyFail()
    {
        $this->userHelper->login("user1", "user1user1");
        $this->url("/me/edit");
        $this->domChecker->clickOnElementMatching("css=form button#change-pwd");
        $this->domChecker->removeHTML5Validation('form[action="/me/edit"]');
        $this->domChecker->fillElements([
           'css=[name="username"]' => '42',
           'css=[name="email"]' => 'badEmail',
           'css=[name="password"]' => 'pwd',
        ]);
        $this->domChecker->clickOnElementMatching('css=form[action="/me/edit"] button[type="submit"]');
        $this->waitForPageToLoad();
        $this->domChecker->assertElementsCount("css=form .alert.alert-danger", 4);
        
    }
}
