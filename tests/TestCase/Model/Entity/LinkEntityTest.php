<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\I18n\Time;

/**
 * App\Model\Entity\Link Test Case
 */
class LinkEntityTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Links' => 'app.links'
    ];
      
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();       
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $this->Links = TableRegistry::get('Links', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Links);

        parent::tearDown();
    }

    /**
     * Test the virtual property remaining_views
     *
     * @return void
     */
    public function testRemainingViews()
    {
        $link = $this->Links->find('all')->first();
        // /!\ Seem that we must first get the value to test correctly      
        $remaining_views = $link->remaining_views;        
        $this->assertNotNull($remaining_views, 'remaining_views virtual field is defined');
        $this->assertEquals($link->max_views - $link->views, $remaining_views, 'remaining_views field is correct');
        
        $noMaxViewsLink = $this->Links->findByTitle('No max_views')->first();
        $remaining_views = $noMaxViewsLink->remaining_views;
        $this->assertNull($remaining_views, 'Remaining_views is null when max_views is null');
    }
    
    /**
     * Test the virtual property life_percentage
     * 
     * @return void
     */
    public function testLifePercentage()
    {
        $link = $this->Links->findByTitle('Half life')->first();        
        $percent = $link->life_percentage;        
        $this->assertNotNull($percent, 'Half life : life_percentage is set');
        $this->assertEquals(50,$percent);
        $deadLink = $this->Links->findByTitle('Dead link by views')->first();        
        $this->assertLessThanOrEqual(100, $deadLink->life_percentage, 'Link percentage is less or equal to 100');
        
        //No max_views is provided
        $noMaxViewsLink = $this->Links->findByTitle('No max_views')->first();
        $percent = $noMaxViewsLink->life_percentage;        
        $this->assertNotNull($percent);        
        $this->assertNotEquals($percent, 0, 'When max_views is null, time is used');
        
        // Fixate time. Look in the fixture 4 with title 'No max_views'
        $now = new Time('1955-11-07 18:38:00');
        Time::setTestNow($now);
        $noMaxViewsLink = $this->Links->findByTitle('No max_views')->first();
        $percent = $noMaxViewsLink->life_percentage;        
        $this->assertEquals($percent, 37.5, 'LifePercentage is good when it is computed from a date');
        
        //No death_time is provided
        $noDeathTimeLink = $this->Links->findByTitle('No death_time')->first();
        $percent = $noDeathTimeLink->life_percentage;        
        $this->assertNotNull($percent);        
        $this->assertEquals($percent, 20, 'When death_time is null, max_views is used');
        
        //Both max_views and death_time fields are specified
        $now = new Time('1955-11-07 18:38:00');
        Time::setTestNow($now);
        $MVAndDTLink = $this->Links->findByTitle('Both max_views and death_time')->first();
        $percent = $MVAndDTLink->life_percentage;        
        $this->assertEquals($percent, 40, 'The closest life remaining time is taken (max_views)');
        $now = new Time('1955-11-09 6:38:00');
        Time::setTestNow($now);        
        $percent = $MVAndDTLink->life_percentage;
        $this->assertEquals($percent, 75, 'The closest life remaining time is taken (death_time)');
    }
}
