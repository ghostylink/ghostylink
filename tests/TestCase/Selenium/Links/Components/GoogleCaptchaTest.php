<?php

/**
 * @group Link
 * @group Functional
 */
class LinkCaptchaTest extends FunctionalTest
{

    public function testCreation()
    {
        // Add a link protected by the captcha and check ui at the creation
        // Do not expect creation
        list($publicURL) = $this->linkHelper->create(
            "",
            "this a content",
            ["google_captcha" => [true]],
            false
        );

        // Component google_captca is assumed to be still here
        $local = 'css=ul#link-components-chosen li[data-related-field="google_captcha"]';
        $this->domChecker->assertElementPresent($local);
        
        list($publicURL) = $this->linkHelper->create(
            "this is a title",
            "this a content",
            ["google_captcha" => [false],
             "death_time" => ['7'] ],
            true
        );

        $this->url($publicURL);
        $this->waitForPageToLoad(10000);
        // Make sure google captcha script is loaded
        sleep(1);
        // ### Check a google captcha is displayed on the link view
        $this->domChecker->assertTextNotPresent("Google captcha");
        $this->domChecker->clickOnElementMatching("css=button#load-link-captcha");
        $this->domChecker->waitUntilElementPresent("css=div.alert-danger");
    }
}
