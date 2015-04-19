<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LinksTable Test Case
 */
class LinksTableTest extends TestCase
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
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        // Check the table creation + attributes
        assertNotEmpty($this->Links);
        assertEquals("links", $this->Links->table());
        assertEquals("id", $this->Links->primaryKey());
        assertEquals(1, $this->Links->hasField('title'));
        assertEquals(1, $this->Links->hasField('content'));
        assertEquals(1, $this->Links->hasField('created'));
        assertEquals(1, $this->Links->hasField('modified'));
        assertEquals(1, $this->Links->hasField('token'));
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {        
        $goodData = [
            'title' => 'I am not in danger ...',
            'content' => 'I am the danger !',
            'token' => md5('Say my name')
        ];
        $nbRecords = $this->Links->find('all')->count();
        //Check the content is required
        $badData1 = $goodData; 
        $badData1['content'] = '';        
        assertFalse($this->Links->save($this->Links->newEntity($badData1)));
        
        //Check the title is required
        $badData2 = $goodData;
        $badData2['title'] = '';        
        assertFalse($this->Links->save($this->Links->newEntity($badData2)));        
        
        //Check the token is required
        $badData3 = $goodData;
        $badData3['token'] = '';
        assertFalse($this->Links->save($this->Links->newEntity($badData3)));
                
        //Check no data has been inserted
        assertEquals($nbRecords, $this->Links->find('all')->count());        
        
        //Check good data can be inserted
        assertNotFalse($this->Links->save($this->Links->newEntity($goodData)));
        assertEquals($nbRecords + 1, $this->Links->find('all')->count());        
        //And the data inserted is ok        
        assertArraySubset($goodData, 
                          $this->Links->find('all')
                                      ->where(['Links.title =' => $goodData['title']])
                                      ->toArray()[0]->toArray());
        
        //Check token has to be unique and its cached by data base
        $badData4 = $goodData;
        $badData4['title'] = $badData4['title'] . date('YYYYMMDD');
        $badData4['content'] = $badData4['content'] . date('YYYYMMDD');
        $this->setExpectedException('PDOException');
        $this->Links->save($this->Links->newEntity($badData4));
    }
}
