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
        $this->domChecker->assertTextPresent("User 1 id 10");
        $this->domChecker->assertTextPresent("2 of 2");
        
        //  Filters (would require  a separate test)
        $this->execute(['script' => '$("#slider-range").slider("values", 0, 0); $("#min_life").val(0);',
                        'args'=> []]);
        $local = 'css=[name="title"]';
        $this->domChecker->typeOnElementMatching($local, "User 1 id 10");
        
        $applyButton = $this->domChecker->findElementMatching("id=apply-filters");
        $applyButton->click();
        $this->waitForPageToLoad();
        $found = $this->domChecker->findElementMatching("css=table tbody tr td");
        $this->assertEquals(1, count($found), 'One link matching the filter');
    }
}
