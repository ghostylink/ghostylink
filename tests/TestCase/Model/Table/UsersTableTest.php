<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 * @group Unit
 * @group Table
 * @group Model
 */
class UsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
    ];

   /**
     * Data which respect the model constraints
     * @var array 
     */
    private $goodData = [
            'username' => 'Walter White',
            'password' => 'dummy_password',
            'email' => 'walter.white@pollos-hermanos.us'
    ];
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        // Check the table creation + attributes
        $this->assertNotEmpty($this->Users);
        $this->assertEquals("users", $this->Users->table());
        $this->assertEquals("id", $this->Users->primaryKey());
        $this->assertEquals(1, $this->Users->hasField('username'));
        $this->assertEquals(1, $this->Users->hasField('email'));
        $this->assertEquals(1, $this->Users->hasField('password'));        
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $goodData = $this->goodData;
        $nbRecords = $this->Users->find('all')->count();                                                     
        
        $goodData = $this->goodData;        
        //Check good data can be inserted
        $link = $this->Users->newEntity();        
        $link = $this->Users->patchEntity($link, $goodData);
        $this->assertNotFalse($this->Users->save($link),
                       'Good data can be inserted');        
        $this->assertEquals($nbRecords + 1, $this->Users->find('all')->count(),
                       'A new record is in DB'); 
        
        //And the data inserted is ok
        $data = $goodData;        
        $this->assertArraySubset($data, 
                          $this->Users->find('all')
                                      ->where(['Users.username =' => $goodData['username']])
                                      ->toArray()[0]->toArray(),
                           'The inserted data corresponds to what we expect');         
    }

    /**
     * Test errors are catched
     */
    public function testErrorsMail()
    {
        $goodData = $this->goodData;
        $badData = $goodData;
        $badData['email'] = 'whalter';
        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $badData);       
        $this->assertFalse($this->Users->save($user), 'Bad email implies non saving');
    }
    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        //$this->markTestIncomplete('Not implemented yet.');
    }
}
