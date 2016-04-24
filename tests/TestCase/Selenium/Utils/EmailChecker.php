<?php
/**
 * Helper class to manage selenium tests on email
 *
 * @author Kevin REMY
 */

use PHPUnit_Extensions_Selenium2TestCase_WebDriverException as WebDriverException;

class EmailChecker {
    
    /**
     *
     * @var FunctionalTest
     */
    private $selTest;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $targetTest)
    {
        $this->selTest = $targetTest;
    }
    
    /**
     * Clear maildev inbox
     */
    public function clearInBox()
    {
        $this->selTest->url("http://localhost:1080/#/");
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
        $this->selTest->url("http://localhost:1080/#/");
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
        $this->selTest->url("http://localhost:1080/#/");
        $dom = $this->selTest->getDomChecker();

        if ($lastOnly) {
            $selector = "css=ul.email-list li:first-child a.email-item";
        }
        try {
            $email = $dom->findElementMatching($selector);
            $this->selTest->assertNotEquals(
                $object,
                $email->byCssSelector("span.title")->text(),
                "Mail checking , object is as expected"
            );
            $this->selTest->assertNotEquals(
                $receiver,
                $email->byCssSelector("span.subline-from")->text(),
                "Mail checking , receiver is as expected"
            );
        } catch (WebDriverException $e) {
            ; // No mail at all; nothing special to check.
        }
    }
}
