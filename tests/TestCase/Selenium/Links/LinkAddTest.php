<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LinkAddTest
 *
 * @author kremy
 */
class LinkAddTest extends FunctionalTest {

    public function testComponentInteraction()
    {
        $this->url("/");
        $this->linkHelper->addDeathTime(7);
        $choosableSelector = 'css=ul#link-components-available li[data-related-field="death_time"]';
        $this->domChecker->assertElementNotPresent($choosableSelector);
        $chosenSelector = 'css=ul#link-components-chosen li[data-related-field="death_time"]';
        $this->domChecker->assertElementPresent($chosenSelector);
        
        $this->domChecker->clickOnElementMatching($chosenSelector);
        $this->domChecker->assertElementNotPresent($chosenSelector);
        $this->domChecker->assertElementPresent($choosableSelector);
    }
    public function testAddNotLogged()
    {
        $this->url("/");
        $this->domChecker->assertElementPresent("css=form#links-add input[type=text]");
        $this->domChecker->assertElementPresent("css=form#links-add textarea");
        $this->domChecker->assertElementPresent("css=[type=submit]");
        $this->domChecker->fillElements([
           'css=input[type=text][name=title]'  => "My super content",
           'css=textarea[name=content]' => "My super title"
        ]);
        $this->domChecker->clickOnElementMatching("css=form#links-add [type=submit]");
        $this->domChecker->waitUntilElementPresent("css=div.alert.alert-danger");
        $this->domChecker->assertTextPresent("At least one limit component is required");
        $this->linkHelper->addDeathTime(7);
        $this->domChecker->clickOnElementMatching("css=form#links-add [type=submit]");
        $this->domChecker->waitUntilElementPresent("css=#link-url");
        $this->domChecker->assertElementNotPresent("css=.alert.alert-danger", "No more error messages");
        $this->domChecker->clickOnElementMatching("css=button.link-copy");
        $this->domChecker->assertTextPresent(
            "Copied !",
            "Instruction is displayed when user click on the copy link button"
        );
    }
    
    public function testAddLogged()
    {
        $this->userHelper->login("user1", "user1user1");
        $this->domChecker->fillElements([
           'css=input[type=text][name=title]'  => "My super title"
        ]);
        $this->domChecker->clickOnElementMatching("css=form#links-add [type=submit]");
        $this->domChecker->waitUntilElementPresent("css=.alert.alert-danger");
        
        $this->domChecker->fillElements([
           'css=textarea[name=content]'  => "My super content"
        ]);
        $this->domChecker->clickOnElementMatching("css=form#links-add [type=submit]");
        $this->domChecker->waitUntilElementPresent("css=#link-url");
    }
}
