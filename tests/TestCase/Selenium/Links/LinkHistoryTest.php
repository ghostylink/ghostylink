<?php
/**
 * @group Link
 * @group Functional
 */
class LinkHistoryTest extends FunctionalTest
{
    public function testNoLink()
    {
        $this->url("/logout");
        $this->userHelper->login("userwithnolink", "userwithnolink");
        
        $this->domChecker->clickOnElementMatching("link=Welcome userwithnolink");
        $this->domChecker->clickOnElementMatching("link=My links");
        $this->waitForPageToLoad();
        $this->domChecker->assertElementNotPresent("css=tr td");
    }
    
    public function testNavigation()
    {
        $this->userHelper->login("user1", "user1user1");
        sleep(120);
        $this->domChecker->clickOnElementMatching("link=Welcome user1");
        $this->domChecker->clickOnElementMatching("link=My links");
        $this->waitForPageToLoad();
        $this->domChecker->assertTextPresent("User 1 id 10");
        // Sort on title
        $this->domChecker->clickOnElementMatching("link=Title");
        $this->waitForPageToLoad();
        $this->domChecker->clickOnElementMatching("link=Title");
        $this->waitForPageToLoad();
        $this->domChecker->assertTextNotPresent("User 1 id 10");
        // Page 2
        $this->domChecker->clickOnElementMatching("link=2");
        $this->waitForPageToLoad();
        sleep(10);
        $this->domChecker->assertTextPresent("User 1 id 10");
        $this->domChecker->assertTextPresent("2 of 2");
        
        // Filters
        $local = "css=[name=\"title\"]";
        $this->domChecker->typeOnElementMatching($local, "User 1 id 10");
//        
//    $this->submit("css=form");
//    $this->waitForPageToLoad("30000");
//    $this->type("css=[name=\"title\"]", "");
//    $this->type("css=[name=\"status\"]", "0");
//    $this->submit("css=form");
//    $this->waitForPageToLoad("30000");
//    $this->assertEquals("0", $this->getCssCount("css=.life-ok"));
    }
    
//    $this->click("link=Welcome user1");
//    $this->click("link=My links");
//    $this->waitForPageToLoad("30000");
//    $this->assertTrue($this->isElementPresent("css=tr td"));
//    $this->assertTrue($this->isTextPresent("User 1 id 10"));
//    $this->click("link=Title");
//    $this->waitForPageToLoad("30000");
//    $this->click("link=Title");
//    $this->waitForPageToLoad("30000");
//    // ### id 10 is on page 2
//    $this->assertFalse($this->isTextPresent("User 1 id 10"));
//    $this->click("link=2");
//    $this->waitForPageToLoad("30000");
//    $this->assertTrue($this->isTextPresent("User 1 id 10"));
//    $this->assertTrue($this->isTextPresent("2 of 2"));
//    // ### Check filters
//    $this->type("css=[name=\"title\"]", "User 1 id 10");
//    $this->submit("css=form");
//    $this->waitForPageToLoad("30000");
//    $this->type("css=[name=\"title\"]", "");
//    $this->type("css=[name=\"status\"]", "0");
//    $this->submit("css=form");
//    $this->waitForPageToLoad("30000");
//    $this->assertEquals("0", $this->getCssCount("css=.life-ok"));
//  }
}
?>