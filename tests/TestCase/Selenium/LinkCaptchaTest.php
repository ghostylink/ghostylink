<?php

/**
 * @group Link
 * @group Functional
 */
class LinkCaptchaTest extends FunctionalTest
{

  public function testLinkCaptcha()
  {
    // ### Check a link can be created with the captcha component
    $this->open("/");
    $this->click("css=ul#link-components-available li[data-related-field=\"google_captcha\"]");
    $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=\"google_captcha\"]"));
    $this->type("css=input[name=\"title\"]", "this is a title");
    $this->type("css=textarea[name=\"content\"]", "this is a content");
    $this->click("css=form[action=\"/add\"] button[type=\"submit\"]");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=div.alert-danger")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->click("css=ul#link-components-available li[data-related-field=\"death_time\"]");
    $this->type("css=input[name=\"death_time\"]", "1");
    $this->click("css=form[action=\"/add\"] button[type=\"submit\"]");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=section.generated-link")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    // ### Check a google captcha is displayed on the link view
    $this->open("/427103fc86a164ccc6a835ea6gd00273");
    $this->assertFalse($this->isTextPresent("Google captcha"));
    $this->click("css=button#load-link-captcha");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=div.alert-danger")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

  }
}
?>