<?php

/**
 * @group Functional
 */
class BasicTest extends FunctionalTest {

    public function testMyTestCase() {
        $this->open("/");
        $this->assertTrue($this->isTextPresent("Ghostylink"));
        $this->assertTrue($this->isElementPresent("css=div#main-content"));
        $this->open('/a1d0c6e83f027327d8461063f4ac58a6');
        $this->assertFalse($this->isTextPresent('was not found on this server'), 'The page is not a 404');
        $this->assertFalse($this->isTextPresent('Record not found'));
    }

}

?>