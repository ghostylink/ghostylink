<?php

/**
 * @group Functional
 */
class BasicTest extends FunctionalTest {
    public function testMyTestCase()
    {
        $this->url("/");
        $this->assertTextPresent('Ghostylink');
        
        $this->assertElementPresent("css=div#main-content");
        $this->url('/a1d0c6e83f027327d8461063f4ac58a6');        
        $this->assertTextNotPresent('was not found on this server', "Check non 404");
        $this->assertTextNotPresent('Not Found', "Check non 404");
    }
}
