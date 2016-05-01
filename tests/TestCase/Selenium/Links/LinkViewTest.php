<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LinkViewTest
 *
 * @author kremy
 * @group Link
 * @group Functional
 */
class LinkViewTest extends FunctionalTest {

    public function testStatisticsDisplayed()
    {
        $this->url("/a1d0c6e83f027327d8461063f4ac58a6");
        $this->domChecker->assertTextNotPresent("Lorem ipsum dolor sit amet");
        $this->domChecker->assertTextNotPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
        $this->domChecker->clickOnElementMatching("css=button#load-link-max_views");
        $this->domChecker->waitUntilTextPresent("Lorem ipsum dolor sit amet");
        $this->domChecker->assertTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
        
        $this->domChecker->assertElementPresent("css=.link-stats");
        $lpElem = $this->domChecker->findElementMatching("css=.link-remaining-views");
        
        $this->assertRegExp("/^\d+ views left/", $lpElem->text());
    }
}
