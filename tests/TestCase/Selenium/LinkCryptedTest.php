<?php
/**
 * @group Functional
 * @group Link
 */
class LinkCryptedTest extends FunctionalTest {

  public function testCryptedLink()
  {
    $this->open("/");
    $this->type("id=inputTitle", "un titre");
    $this->type("id=inputContent", "un");
    $this->type("id=inputTitle", "a title");
    $this->type("id=inputContent", "a private and cryped content");
    // # Check the non crypted value is displayed on errors
    $this->click("css=form[action=\"/add\"] button");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=form .alert-danger")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->assertEquals("a private and cryped content", $this->getValue("css=[name=\"content\"]"));
    // # Check the message is crypted
    $this->click("css=[data-related-field=\"death_time\"]");
    $this->type("id=inputContent", "a private and crypted content");
    $this->click("css=form[action=\"/add\"] button");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=section.generated-link #link-url")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->runScript("location.replace($('#link-url').text())");
    $this->waitForPageToLoad("10000");
    $this->assertEquals("a private and crypted content", $this->getText("css=section.link-content"));
    // # Check the message cannot be decrypted if key is not in url
    $this->runScript("loc = window.location.href;location.replace(loc.substring(0, loc.indexOf('#')))");
    $this->waitForPageToLoad("10000");
    $this->assertFalse($this->isTextPresent("a private and crypted content"));
  }
}
?>