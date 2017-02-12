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
        
        exec('env CI_SERVER=1 $(pwd)/bin/cake mailer alerts');
        $this->emailChecker->assertMailNotReceived("testnotifs@gmail.com", "Ghostification alert");
        
        // # Adding a link which will be seen
        $this->url("/");
        $this->domChecker->fillElements([
                "id=inputTitle" => "testing mail IS sent",
                "id=inputContent" => "private content mail sending"
        ]);
        
        $this->domChecker->clickOnElementMatching("css=[data-related-field=\"max_views\"]");
        $this->domChecker->clickOnElementMatching("css=[data-related-field=\"alert_parameter\[life_threshold\]\"]");
        
        $this->domChecker->fillElements(['css=[name="max_views"]' => "4"]);

        $this->domChecker->clickOnElementMatching("css=form[action=\"/\"] button");
        
        $this->domChecker->waitUntilElementPresent("css=section.generated-link #link-url");
        
        $linkUrl = $this->execute(array('script' => "return $('#link-url').text().trim();",
                             'args' => array()));

        $linkUrl = parse_url($linkUrl, PHP_URL_PATH);

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
//        $this->execute(
//            ['script' => "window.removeEventListener('beforeunload', beforeunload)",
//            'args' => []
//            ]
//        );
        exec('env CI_SERVER=1 $(pwd)/bin/cake mailer alerts');
        $this->emailChecker->assertMailReceived("testnotifs@gmail.com", "Ghostification alert");
        $this->emailChecker->clearInBox();
        
        exec('env CI_SERVER=1 $(pwd)/bin/cake mailer alerts');
        $this->refresh();
        $this->emailChecker->assertMailNotReceived("testnotifs@gmail.com", "Ghostification alert");
    }
}
