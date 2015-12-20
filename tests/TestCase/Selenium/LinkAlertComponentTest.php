<?php
/**
 * @group Functional
 * @group Link
 */
class LinkAlertComponentTest extends FunctionalTest
{

  public function testMailSending()
  {
    /*********** Clear maildev inbox *************/
    $this->open("http://localhost:1080/#/");
    $this->click('css=.toolbar a[ng-click="deleteAll()"]');
    /*********** Clear maildev inbox *************/
    $this->open("/logout");
    $this->type("id=username", "testnotifs");
    $this->type("id=password", "testnotifs");
    $this->click("css=button.btn.btn-default");
    $this->waitForPageToLoad("30000");
    // # Adding a link which will not be seen
    $this->type("id=inputTitle", "testing mail NOT sent ");
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
    exec('$(pwd)/bin/cake mailer alerts');
    $this->open("http://localhost:1080/#/");
    $this->assertFalse($this->isTextPresent("testnotifs@gmail.com"));
    // # Adding a link which will be seen
    $this->open("/");
    $this->type("id=inputTitle", "testing mail IS sent");
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
            if ($this->isTextPresent("testing mail IS sent")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->refresh();
    $this->waitForPageToLoad("30000");
    $this->click("css=#load-link-max_views");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("testing mail IS sent")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->refresh();
    $this->waitForPageToLoad("30000");
    $this->click("css=#load-link-max_views");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("testing mail IS sent")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    // # Check mail is sent (based with maildev tool)
    exec('$(pwd)/bin/cake mailer alerts');
    $this->open("http://localhost:1080/#/");
    $this->assertTextPresent("testnotifs@gmail.com");
    $this->click('css=.toolbar a[ng-click="deleteAll()"]');

    // # Check mail is not sent twice (based with maildev tool)
    exec('$(pwd)/bin/cake mailer alerts');
    $this->refreshAndWait();
    $this->assertFalse($this->isTextPresent("testnotifs@gmail.com"));
  }
}
?>