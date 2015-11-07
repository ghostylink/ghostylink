<?php
/**
 * @group Functional
 * @group Link
 */
class LinkAlertComponentTest extends FunctionalTest
{

  public function testMailSending()
  {
    $this->open("/logout");
    $this->type("css=form[action=\"/login\"] #username", "testnotifs");
    $this->type("css=form[action=\"/login\"] #password", "testnotifs");
    $this->clickAndWait("css=form[action=\"/login\"] .btn-default");
    // # Adding a link which will not be seen
    $this->type("id=inputTitle", "testing mail sending");
    $this->type("id=inputContent", "private content mail sending");
    $this->click("css=[data-related-field=\"max_views\"]");
    $this->click("css=[data-related-field=\"ghostification_alert\"]");
    $this->type("css=[name=\"max_views\"]", "3");
    $this->type("css=[name=\"AlertParameters[life_threshold]\"]", "25");
    $this->click("css=form[action=\"/add\"] button");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isElementPresent("css=section.generated-link #link-url")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    // # Check mail is not sending (based with maildev tool)
    debug(exec('$(pwd)/bin/cake mailer alerts'));
    $this->open("http://localhost:1080/#/");
    $this->assertFalse($this->isTextPresent("testnotifs@gmail.com"));
    // # Adding a link which will be seen
    $this->open("/");
    $this->type("id=inputTitle", "testing mail sending");
    $this->type("id=inputContent", "private content mail sending");
    $this->click("css=[data-related-field=\"max_views\"]");
    $this->click("css=[data-related-field=\"ghostification_alert\"]");
    $this->type("css=[name=\"max_views\"]", "3");
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
    $this->click("css=#load-link-max_views");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("testing mail sending")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->refresh();
    $this->waitForPageToLoad("30000");
    $this->click("css=#load-link-max_views");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("testing mail sending")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    // # Check mail is not sending (based with maildev tool)
    debug(exec('$(pwd)/bin/cake mailer alerts'));
    $this->open("http://localhost:1080/#/");
    $this->assertTrue($this->isTextPresent("testnotifs@gmail.com"));
    $this->click('css=.toolbar a[ng-click="deleteAll()"]');

    // # Check mail is not sending twice (based with maildev tool)
    debug(exec('$(pwd)/bin/cake mailer alerts'));
    $this->refresh();
    $this->assertFalse($this->isTextPresent("testnotifs@gmail.com"));
  }
}
?>