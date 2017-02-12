<?php
/**
 * Description of EmailHelper
 *
 * @author kremy
 */
class LinkHelper {

    /**
     *
     * @var FunctionalTest
     */
    private $selTest;
    
    /**
     * @var array
     */
    const COMPONENTS_MAPPING = [
        "google_captcha" => 'addCaptcha',
        'death_time' => 'addDeathTime'
    ];

    public function __construct(PHPUnit_Extensions_Selenium2TestCase $targetTest)
    {
        $this->selTest = $targetTest;
    }
    
    /**
     * Create a link with the given title, the given content
     * @param string $title Title of the link to create
     * @param string $content The private content of the link
     * @param array<map> $componentsList list of component to add to the link
     * @param boolean $expectCreation true if creation is expected to succeed
     * @param boolean $fullURL true if entire (with host) url has to be return
     * @return array containing public url at first index, private at second index
     */
    public function create($title, $content, $componentsList = [], $expectCreation = true, $fullURL = false)
    {
        $this->selTest->url("/");
        // We do not check HTML5 validation yet so let put removing here
        $this->selTest->getDomChecker()->removeHTML5Validation("form#links-add");
        $this->selTest->getDomChecker()->fillElements([
            "id=inputTitle" => $title,
            "id=inputContent" => $content,
        ]);
        $mapping = LinkHelper::COMPONENTS_MAPPING;

        foreach ($componentsList as $component => $arguments) {
            $method = $mapping[$component];
            $this->{$method}(...$arguments);
        }

        $urls = $this->submit($expectCreation, $fullURL);

        if ($expectCreation) {
            return $urls;
        }
        // We did not expect creation to succeed, wait for error
        $this->selTest->getDomChecker()->waitUntilElementPresent("css=div.alert-danger");
    }
    
    /**
     * Add a captcha component
     * @param bool $testUI true if UI has to be checked when component is chosen
     */
    private function addCaptcha($testUI = false)
    {
        $selector = 'css=[data-related-field="google_captcha"]';
        $this->chooseComponent($selector);
        
        if ($testUI) {
            $selector = 'css=ul#link-components-chosen li[data-related-field="google_captcha"]';
            $this->selTest->getDomChecker()->assertElementPresent($selector);
        }
    }
    
    /**
     * Add a death time component with the given number of days
     * @param int $nbOfDays
     */
    public function addDeathTime($nbOfDays)
    {
        $selector = 'css=li[data-related-field="death_time"]';
        $this->selTest->getDomChecker()->clickOnElementMatching($selector);
        $selector = 'css=label[for="death-time-' . $nbOfDays . '"]';
        $this->selTest->getDomChecker()->clickOnElementMatching($selector);
    }
    
    /**
     * Choose a component by clicking on the given selector
     * @param type $selector
     */
    private function chooseComponent($selector)
    {
        $this->selTest->getDomChecker()->clickOnElementMatching($selector);
    }
    
    /**
     * Retrieve generated url at the link creation assuming we are on the home
     * page
     * @param boolean $fullURL true if domain must be in the url
     * @return type
     */
    private function retrieveGeneratedUrls($fullURL = false)
    {
        $selector = "css=section.generated-link #link-url";
        $this->selTest->getDomChecker()->waitUntilElementPresent($selector);
        $publicURL = $this->selTest->getDomChecker()
                                   ->findElementMatching($selector)->text();
        if (! $fullURL) {
            $parsed = parse_url($publicURL);
            $publicURL = $parsed["path"] . '#' . $parsed['fragment'];
        }
        $privateURL = "";
        return array($publicURL, $privateURL);
    }
    
    /**
     * Submit the link form
     * @return array containing public url at first index, private at second index
     */
    public function submit($expectedCreation = true, $fullURL = false)
    {
        $addButton = 'css=form[action="/"] button';
        $this->selTest->getDomChecker()->clickOnElementMatching($addButton);

        if ($expectedCreation) {
            return $this->retrieveGeneratedUrls($fullURL);
        } else {
            return array(2);
        }
    }
}
