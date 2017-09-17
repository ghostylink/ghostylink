<?php
/**
 * Helper class to manage selenium tests on email
 *
 * @author Kevin REMY
 */

use PHPUnit_Extensions_Selenium2TestCase_WebDriverException as WebDriverException;
use Cake\Core\Configure;

class EmailChecker {
    
    /**
     *
     * @var FunctionalTest
     */
    private $selTest;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $targetTest)
    {
        $this->selTest = $targetTest;
        $this->mailDevHost = Configure::read("EmailTransport.default.host");
        $this->mailDevWebPort = Configure::read("EmailTransport.default.webport");
    }
    
    /**
     * Clear maildev inbox
     */
    public function clearInBox()
    {
        print_r("-----------------------------------------------");
        print_r("http://$this->mailDevHost:$this->mailDevWebPort");
        $this->selTest->url("http://$this->mailDevHost:$this->mailDevWebPort");
        $clearButtonSelector = 'css=.toolbar a[ng-click="deleteAll()"]';
        $DOM = $this->selTest->getDomChecker();
        $DOM->clickOnElementMatching($clearButtonSelector);
    }
    
    /**
     * @param string $receiver the email of the supposed receiver
     * @param string $object the object of the email
     * @param boolean $lastOnly true if only the last received email must be checked
     */
    public function assertMailReceived($receiver, $object, $lastOnly = true)
    {
        $this->selTest->url("http://$this->mailDevHost:$this->mailDevWebPort/#/");
        $dom = $this->selTest->getDomChecker();

        if ($lastOnly) {
            $selector = "css=ul.email-list li:first-child a.email-item";
        }
        $email = $dom->findElementMatching($selector);
        
        $this->selTest->assertEquals(
            $object,
            $email->byCssSelector("span.title")->text(),
            "Mail checking , object is as expected"
        );
        
        $this->selTest->assertEquals(
            $receiver,
            $email->byCssSelector("span.subline-from")->text(),
            "Mail checking , receiver is as expected"
        );
    }
    
    /**
     * Assert a mail is NOT received
     * @param string $receiver the email of the non desired receiver
     * @param string $object the object of the non desired email
     * @param boolean $lastOnly true if only the last received email must be checked
     */
    public function assertMailNotReceived($receiver, $object, $lastOnly = true)
    {
        $this->selTest->url("http://$this->mailDevHost:$this->mailDevWebPort");
        $dom = $this->selTest->getDomChecker();

        if ($lastOnly) {
            $selector = "css=ul.email-list li:first-child a.email-item";
        }
        try {
            $email = $dom->findElementMatching($selector);
            $actualObject = $email->byCssSelector("span.title")->text();
            $actualReceiver = $email->byCssSelector("span.subline-from")->text();
            $this->selTest->assertFalse(
                $receiver == $actualReceiver && $actualObject == $object,
                "$actualReceiver received a mail with object '$object' and it was not expected"
            );
        } catch (WebDriverException $e) {
            ; // No mail at all; nothing special to check.
        }
    }
}
