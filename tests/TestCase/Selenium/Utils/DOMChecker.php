<?php
/**
 * Helper class to manage selenium tests on the DOM page
 *
 * @author Kevin REMY
 */

use PHPUnit_Extensions_Selenium2TestCase_WebDriverException as WebDriverException;

class DOMChecker {
    
    private $selTest;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $targetTest)
    {
        $this->selTest = $targetTest;
    }
    
    /**
     * Simulate keyboard typping the given value on the element matching
     * the given selector
     * @param string $local the localizator
     * @param string $newValue the value to type in field
     * @see DOMChecker::findElementMatching($local) for the selection method
     */
    public function typeOnElementMatching($local, $newValue)
    {
        $found = $this->findElementMatching($local);
        $found->value($newValue);
    }
    
    /**
     * Type given value for each given elements
     * @param array values. Expecting associative array like
     *                      ['sel1' => 'val1', 'sel2' => 'val2']
     */
    public function fillElements(array $values)
    {
        foreach ($values as $selector => $fillValue) {
            $this->typeOnElementMatching($selector, $fillValue);
        }
    }
    /**
     * Find element matching $local expression
     * @param  string $local the localizator
     * @throws WebDriverException if not found
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element the found element
     */
    public function findElementMatching($local)
    {
        $selectorPart = explode("=", $local, 2);
        if ($selectorPart[0] == "css") {
            $found = $this->selTest->byCssSelector($selectorPart[1]);
        } elseif ($selectorPart[0] == "id") {
            $found = $this->selTest->byId($selectorPart[1]);
        } else {
            $this->selTest->assertFalse("Selection by $selectorPart[0] not yet implemented");
        }
        return $found;
    }
    
    /**
     * Wait until the element localied by $local is in the page
     * @param <string> $local the localizator
     * @param <int> $timeout maximum time to wait
     * @see DOMChecker::findElementMatching($local) for the selection methods
     */
    public function waitUntilElementPresent($local, $timeout = 10)
    {
        $second = 0;
        while ($second < $timeout && !$this->isElementPresent($local)) {
            sleep(1);
            $second++;
        }
        if (!$this->isElementPresent($local)) {
            $this->selTest->fail("timeout while trying to find element matching '$local'");
        }
    }
    
    /**
     * @param  <string> $local
     * @see DOMChecker::findElementMatching($local) for localization methods
     * @return <boolean> true if element is present, false otherwise
     */
    public function isElementPresent($local)
    {
        try {
            $this->findElementMatching($local);
            return true;
        } catch (WebDriverException $ex) {
            return false;
        }
    }
    /**
     * Wait until text is present in the source page
     * @param <string> $text text to look fo
     * @param <int> $timeout maximal waiting time
     */
    public function waitUntilTextPresent($text, $timeout = 10)
    {
        $second = 0;
        while ($second < $timeout && !$this->isTextPresent($text)) {
            sleep(1);
            $second++;
        }
        if (!$this->isTextPresent($text)) {
            $this->selTest->fail("timeout while trying to find text matching '$text'");
        }
    }
    /**
     * Click on the element matching the given selector
     * @param string $local the localizator
     * @param boolean $waitElementPresent true if need to wait element to be present
     * @throws WebDriverException if not found
     * @see DOMChecker::findElementMatching() for the selection methods
     */
    public function clickOnElementMatching($local, $waitElementPresent = true)
    {
        if ($waitElementPresent) {
            $this->waitUntilElementPresent($local);
        }
        $found = $this->findElementMatching($local);
        $found->click();
    }
    
    /**
     * Assert at least one element is present in the page.
     * @param <string> $local localisation string. Start css=expr or id=expr
     * @see DOMChecker::findElementMatching($local) for the selection methods
     */
    public function assertElementPresent($local)
    {
        $found = $this->findElementMatching($local);
        $this->selTest->assertGreaterThan(
            0,
            count($found),
            "At least one element matching $local is found"
        );
    }
    
    /**
     * Assert a text is present in the current page
     * @param <string> $text text to find in page
     * @param <string> $prefixMsg A prefix message to add in case of failure
     */
    public function assertTextPresent($text, $prefixMsg = "")
    {
        $source = $this->selTest->source();
        $this->selTest->assertContains(
            $text,
            $source,
            "$prefixMsg Text '$text' is present in page"
        );
    }
    
    /**
     * Assert a text is not present in the current page
     * @param <string> $text text that should not be present
     * @param <string> $prefixMsg A prefix message to add in case of failure
     */
    public function assertTextNotPresent($text, $prefixMsg = "")
    {
        $source = $this->selTest->source();
        $this->selTest->assertNotContains($text, $source, "$prefixMsg Text '$text' is not present in page");
    }
    
    /**
     * Check a text is present in the current page
     * @param type $text
     * @return <boolean> True if text is present, False otherwise
     */
    public function isTextPresent($text)
    {
        $source = $this->selTest->source();
        return strpos($source, $text) !== false;
    }
    
    public function assertElementHasValue($local, $expected)
    {
        $element = $this->findElementMatching($local);
        $this->selTest->assertEquals(
            $element->attribute("value"),
            $expected,
            "Assert element " . $local . " as value '" . $expected . "'"
        );
        
    }
    
    /**
     * Retrieve the text of the given element
     * @param string $local the localization
     * @return string text of the matching element
     */
    public function getTextOf($local)
    {
        $elem = $this->findElementMatching($local);
        return $elem->text();
    }
}
