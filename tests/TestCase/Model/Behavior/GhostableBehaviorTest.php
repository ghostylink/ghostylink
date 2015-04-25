<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LinksTable Test Case
 */
class GhostableBehaviorTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        GhostableBehaviorTest::TARGET_TABLE => 'app.links'
    ];

    //The table to test the behavior on
    CONST TARGET_TABLE = 'Links';
    
    /**
     * The save function should not return false using this data
     * @var array 
     */
    private  $goodData = [
            'title' => 'I am not in danger ...',
            'content' => 'I am the danger !',
            'token' => 'Say my name',
            'max_views' => 8 // big default value to avoid unexpected behaviors
    ];
    
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();       
        $config = TableRegistry::exists(GhostableBehaviorTest::TARGET_TABLE) ? [] 
                                : ['className' => 'App\Model\Table\\' . GhostableBehaviorTest::TARGET_TABLE . 'Table'];
        $this->TargetTable = TableRegistry::get(GhostableBehaviorTest::TARGET_TABLE, $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TargetTable);

        parent::tearDown();
    }
  
    /**
     * Test the view attribute is increased
     * @return void
     */
    public function testIncreaseViews() {
        $goodData = $this->goodData;
        $goodData['title'] = 'titletestIncreaseView';
        $entity = $this->TargetTable->newEntity($goodData);
        $this->TargetTable->save($entity);
        $viewsBefore = $this->TargetTable->find('all')
                           ->where(['title =' => $goodData['title']])
                           ->toArray()[0]->views;
        
        //Apply the behavior function;
        $behavior = $this->TargetTable->behaviors()->get('Ghostable');                        
        $behavior->increaseViews($entity);
        
        //Save the increased entity
        $this->TargetTable->save($entity);
        
        //Check we have effectively increased the field
        $viewsAfter = $this->TargetTable->find('all')
                           ->where(['title =' => $goodData['title']])
                           ->toArray()[0]->views;
        $this->assertEquals($viewsBefore + 1, $viewsAfter);               
    }
}
