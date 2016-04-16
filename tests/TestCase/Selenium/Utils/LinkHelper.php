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
     * @param boolean $fullURL true if entire (with host) url has to be return
     * @param boolean $waitCreation true if entire (with host) url has to be return
     * @return array containing public url at first index, private at second index
     */
    public function create($title, $content, $componentsList = [], $fullURL = false, $waitCreation = true)
    {
        $this->selTest->url("/");
        $this->selTest->getDomChecker()->fillElements([
            "id=inputTitle" => $title,
            "id=inputContent" => $content,
        ]);
        $mapping = LinkHelper::COMPONENTS_MAPPING;

        foreach ($componentsList as $component => $arguments) {
            $method = $mapping[$component];
            $this->{$method}(...$arguments);
        }

        $addButton = 'css=form[action="/add"] button';
        $this->selTest->getDomChecker()->clickOnElementMatching($addButton);

        if (! $waitCreation) { return; }
        
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
     * Add a captcha component
     */
    private function addCaptcha()
    {
        
        $this->selTest->getDomChecker()->clickOnElementMatching($selector);
    }
    
    /**
     * Add a death time component with the given number of days
     * @param int $nbOfDays
     */
    private function addDeathTime($nbOfDays)
    {
        $selector = 'css=[data-related-field="death_time"]';
        $this->selTest->getDomChecker()->clickOnElementMatching($selector);
        $selector = 'css=label[for="death-time-' . $nbOfDays . '"] span';
        $this->selTest->getDomChecker()->clickOnElementMatching($selector);
    }
}
