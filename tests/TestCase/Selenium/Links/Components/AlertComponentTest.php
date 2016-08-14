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
        $this->emailChecker->clearInBox();
        
        $this->userHelper->login("testnotifs", "testnotifs");
        
        // # Adding a link which will not be seen
        $this->domChecker->fillElements([
            "id=inputTitle" =>  "testing mail NOT sent ",
            "id=inputContent" => "private content mail sending"
        ]);

        $this->domChecker->clickOnElementMatching('css=[data-related-field="max_views"]');
        $this->domChecker->clickOnElementMatching("css=[data-related-field=\"alert_parameter\[life_threshold\]\"]");
        
        $this->domChecker->removeHTML5Attribute(
            "[name=\"AlertParameters[life_threshold]\"]",
            "readonly"
        );
        $alert_parameter_value = "[name=\"alert_parameter[life_threshold]\"]";
        $this->domChecker->removeHTML5Attribute($alert_parameter_value, "readonly");
        $this->domChecker->fillElements([
            "css=[name=\"max_views\"]" =>  "3",
            "css=" . $alert_parameter_value =>  "25"
        ]);
        
        $this->domChecker->clickOnElementMatching("css=form[action=\"/\"] button");
        
        $this->domChecker->waitUntilElementPresent("css=section.generated-link #link-url");

        // # Check mail is not sending (based with maildev tool)
        exec('env CI_SERVER=1 $(pwd)/bin/cake mailer alerts');
        $this->url("http://localhost:1080/#/");
        $this->domChecker->assertTextNotPresent("testnotifs@gmail.com");
        
        // # Adding a link which will be seen
        $this->url("/");
        $this->domChecker->fillElements([
                "id=inputTitle" => "testing mail IS sent",
                "id=inputContent" => "private content mail sending"
        ]);
        
        $this->domChecker->clickOnElementMatching("css=[data-related-field=\"max_views\"]");
        $this->domChecker->clickOnElementMatching("css=[data-related-field=\"alert_parameter\[life_threshold\]\"]");
        
        $this->domChecker->fillElements(['css=[name="max_views"]' => "3" ]);

        $this->domChecker->clickOnElementMatching("css=form[action=\"/\"] button");
        
        $this->domChecker->waitUntilElementPresent("css=section.generated-link #link-url");
        
        $linkUrl = $this->execute(array('script' => "return $('#link-url').text();",
                             'args' => array()));

        $linkUrl = substr($linkUrl, strpos($linkUrl, ":8765") + 5);

        $this->url($linkUrl);

        $this->waitForPageToLoad(10000);
        
        $this->domChecker->clickOnElementMatching("css=#load-link-max_views");

        $this->domChecker->waitUntilTextPresent("testing mail IS sent");

        $this->refresh();
        $this->waitForPageToLoad(10000);

        $this->domChecker->clickOnElementMatching("css=#load-link-max_views");
        $this->domChecker->waitUntilTextPresent("testing mail IS sent");

        $this->refresh();
        $this->waitForPageToLoad(10000);

        $this->domChecker->clickOnElementMatching("css=#load-link-max_views");
        $this->domChecker->waitUntilTextPresent("testing mail IS sent");

        // # Check mail is sent (based with maildev tool)
        exec('env CI_SERVER=1 $(pwd)/bin/cake mailer alerts');
        $this->url("http://localhost:1080/#/");
        $this->domChecker->assertTextPresent("testnotifs@gmail.com");
        $this->emailChecker->clearInBox();

        // # Check mail is not sent twice (based with maildev tool)
        exec('env CI_SERVER=1 $(pwd)/bin/cake mailer alerts');
        $this->refresh();
        $this->domChecker->assertTextNotPresent("testnotifs@gmail.com");
    }
}
