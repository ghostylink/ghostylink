<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

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
    }
    
    /**
     * Test the virtual prperty life_percentage
     * 
     * @return void
     */
    public function testLifePercentage()
    {
        $link = $this->Links->findByTitle('Half life')->first();        
        $percent = $link->life_percentage;        
        $this->assertNotNull($percent);
        $this->assertEquals($percent, 50);
    }
}
