<?php
/**
 * @group Functional
 * @group Link
 */
class LinkViewTest extends FunctionalTest
{

  public function testLinkView()
  {
    $this->open("/a1d0c6e83f027327d8461063f4ac58a6");
    // Check the link itself is displayed
    // It has a max_views, check the information is not yet present
    $this->assertFalse($this->isTextPresent("Lorem ipsum dolor sit amet"));
    $this->assertFalse($this->isTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat."));
    $this->click("css=button#load-link");
    for ($second = 0; ; $second++) {
        if ($second >= 60) $this->fail("timeout");
        try {
            if ($this->isTextPresent("Lorem ipsum dolor sit amet")) break;
        } catch (Exception $e) {}
        sleep(1);
    }

    $this->verifyTextPresent("Lorem ipsum dolor sit amet, aliquet feugiat.");
    $this->verifyTextPresent("Lorem ipsum dolor sit amet");
    $this->assertFalse($this->isElementPresent("css=a.delete-link"));
    // Check links statistics are displayed
    $this->assertTrue($this->isElementPresent("css=.link-stats"));
    $this->assertTrue((bool)preg_match('/^Ghostified at [\s\S]*$/',$this->getText("css=meter.link-life-percentage")));
    $this->assertTrue((bool)preg_match('/^0 views left[\s\S]*$/',$this->getText("css=meter.link-remaining-views+div")));
    // No max_views, check the information is displayed in 1 step
    $this->open("/6c6e83f027327d846103f4ac58a6a1d0");
    $this->assertFalse($this->isElementPresent("css=section.unloaded button"));
    $this->assertFalse($this->isElementPresent("css=section.unloaded img"));
  }
}
?>