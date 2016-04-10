<?php
/**
 * Helper class to manage selenium tests on email
 *
 * @author Kevin REMY
 */
class EmailChecker {
    
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
}
