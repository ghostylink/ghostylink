<?php

/**
 * @group Functional
 * @group Link
 */
class LinksTest extends FunctionalTest {

    public function testAdd() {
        // Check that basic element are present
        $this->open("/");
        try {
            $this->assertTrue($this->isElementPresent("css=form#links-add input[type=text]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertTrue($this->isElementPresent("css=form#links-add textarea"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertTrue($this->isElementPresent("css=[type=submit]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        // Fill up the fields
        $this->type("css=input[type=text][name=title]", "My super content");
        $this->type("css=textarea[name=content]", "My super title");
        $this->click("css=ul#link-components-available li#[data-related-field=max_views]");
        $this->type("css=input[name=max_views]", "42");
        $this->click("css=form#links-add [type=submit]");
        for ($second = 0;; $second++) {
            if ($second >= 60)
                $this->fail("timeout");
            try {
                if ($this->isElementPresent("css=section.generated-link"))
                    break;
            } catch (Exception $e) {

            }
            sleep(1);
        }

        // Check we have the link
        $this->assertTrue($this->isElementPresent("css=section.generated-link"));
        // Click on the select button
        $this->click("css=button.link-copy");
        $this->assertTextPresent("Press Ctlr+C to copy !");
    }

    public function testAddComponentsWithSubmit() {
        // ###################################
        // Check  the component iteraction is still here when errors are retrieved
        // ###################################
        // When the error is not on a component field
        $this->open("/");
        $this->type("css=input[name=title]", "Myawesome title");
        $this->click("css=ul#link-components-available li[data-related-field=max_views]");
        $this->type("css=input[name=max_views]", "2");
        $this->click("css=form#links-add [type=submit]");
        for ($second = 0;; $second++) {
            if ($second >= 60)
                $this->fail("timeout");
            try {
                if ($this->isElementPresent("css=div.error textarea"))
                    break;
            } catch (Exception $e) {

            }
            sleep(1);
        }

        $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
        $this->assertTrue($this->isElementPresent("css=input[name=max_views]"));
        $this->type("css=input[name=title]", "");
        $this->type("css=textarea[name=content]", "Myawesome contenet");
        $this->click("css=form#links-add [type=submit]");
        for ($second = 0;; $second++) {
            if ($second >= 60)
                $this->fail("timeout");
            try {
                if ($this->isElementPresent("css=div.error input[name=title]"))
                    break;
            } catch (Exception $e) {

            }
            sleep(1);
        }

        $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
        $this->assertTrue($this->isElementPresent("css=input[name=max_views]"));
        // When the error is on a component field
        $this->open("/");
        $this->type("css=input[name=title]", "Myawesome title");
        $this->type("css=textarea[name=content]", "My awesome private content");
        $this->click("css=ul#link-components-available li[data-related-field=max_views]");
        $this->click("css=form#links-add [type=submit]");
        // Checks the components is removed when we click on it
        $this->click("css=ul#link-components-chosen li[data-related-field=max_views]");
        $this->assertTrue($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
        $this->assertFalse($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
        $this->assertFalse($this->isElementPresent("form div.error"));
        $this->assertFalse($this->isElementPresent("css=fieldset input[name=max_views]"));
        $this->assertFalse($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
        // Checks the components is moved when we click on it
        $this->click("css=ul#link-components-available li[data-related-field=max_views]");
        $this->assertFalse($this->isTextPresent("Drop some components here"));
        $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
        $this->assertFalse($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
        $this->assertTrue($this->isElementPresent("css=fieldset input[name=max_views]"));
        $this->assertTrue($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
    }

    public function testAddComponentsNoSubmit() {
        $this->open("/");
        $this->assertTrue($this->isTextPresent("Drop some components here"));
        // Checks the components is moved when we click on it
        $this->click("css=ul#link-components-available li[data-related-field=max_views]");
        $this->assertFalse($this->isTextPresent("Drop some components here"));
        $this->assertTrue($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
        $this->assertFalse($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
        // Checks the field + the flag have been added
        $this->assertTrue($this->isElementPresent("css=fieldset input[name=max_views]"));
        $this->assertTrue($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
        // Checks the components is removed when we click on it
        $this->click("css=ul#link-components-chosen li[data-related-field=max_views]");
        $this->assertTrue($this->isElementPresent("css=ul#link-components-available li[data-related-field=max_views]"));
        $this->assertFalse($this->isElementPresent("css=ul#link-components-chosen li[data-related-field=max_views]"));
        // Checks the field + flage have removed from the form
        $this->assertFalse($this->isElementPresent("css=fieldset input[type=hidden][name=flag-max_views]=max_views]"));
        $this->assertFalse($this->isElementPresent("css=fieldset input[name=max_views]"));
    }

}

?>