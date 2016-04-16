<?php

/**
 * Helper to manage selenium actions on user entity
 *
 * @author kremy
 */
class UserHelper {

    private $selTest;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $targetTest)
    {
        $this->selTest = $targetTest;
    }
    
    /**
     * Log in the user identified by the given username/password
     * @param string $username
     * @param string $password
     * @param boolean $logout true if a logout action should be performed before
     */
    public function login($username, $password, $logout = true)
    {
        if ($logout) {
            $this->logout();
        }
        $this->selTest->getDomChecker()->fillElements([
            "id=username" => $username,
            "id=password" => $password
        ]);
        
        //TODO: change for a better css selector
        $this->selTest->getDomChecker()->clickOnElementMatching("css=button.btn.btn-default");
        $this->selTest->waitForPageToLoad(30000);
    }
    
    public function logout()
    {
        $this->selTest->url("/logout");
    }
}
