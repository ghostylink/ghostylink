<?php

/**
 * @group Functional
 */
class rootTest extends FunctionalTest {

    public function testFormIsPresent() {
        $this->open("/");
        $this->assertTrue($this->isElementPresent("css=form[action=\"/add\"]"));
        $this->assertEquals("1", $this->getCssCount("form[action=\"/add\"] input[type=\"text\"]"));
        $this->assertEquals("1", $this->getCssCount("form[action=\"/add\"] textarea"));
    }

}

?>