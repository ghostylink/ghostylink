<?php
/**
 * @group Functional
 * @group Link
 */
class LinkCryptedTest extends FunctionalTest
{

    public function testCryptedLink()
    {
        $content = "a private and crypted content";
        $this->linkHelper->create("", $content, [], false, false);
        
        $this->domChecker->waitUntilElementPresent("css=form .alert-danger");
        $this->domChecker->assertElementHasValue('css=[name="content"]', $content);

        list($publicUrl) = $this->linkHelper->create(
            "a public title",
            $content,
            ["death_time" => ['7']]
        );
        $this->url($publicUrl);
        $this->waitForPageToLoad(10000);
        sleep(1);
        $this->assertEquals($content, $this->domChecker->getTextOf("css=section.link-content"));
        
        // Check the message cannot be decrypted if key is not in url
        list ($urlNoKey) = explode('#', $publicUrl);
        $this->url($urlNoKey);
        $this->waitForPageToLoad(10000);
        sleep(1);
        $this->domChecker->assertTextNotPresent($content, "Checking unable to decrypt without key");
    }
}
