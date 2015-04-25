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
        $this->assertNotEmpty($this->Links);
        $this->assertEquals("links", $this->Links->table());
        $this->assertEquals("id", $this->Links->primaryKey());
        $this->assertEquals(1, $this->Links->hasField('title'));
        $this->assertEquals(1, $this->Links->hasField('content'));
        $this->assertEquals(1, $this->Links->hasField('created'));
        $this->assertEquals(1, $this->Links->hasField('modified'));
        $this->assertEquals(1, $this->Links->hasField('token'));
        $this->assertEquals(1, $this->Links->hasField('max_views'),
                            'max_views field is present');
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
            'token' => md5('Say my name'),
            'max_views' => 80000 // big default value to avoid unexpected behaviors
        ];
        $nbRecords = $this->Links->find('all')->count();
        //Check the content is required
        $badData1 = $goodData; 
        $badData1['content'] = '';        
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData1)));
        
        //Check the title is required
        $badData2 = $goodData;
        $badData2['title'] = '';        
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData2)));        
        
        //Check the token is required ...
        $badData3 = $goodData;
        unset($badData3['token']);
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData3)), 
                    'Token is required');
        $badData3['token'] = '';
        //.. and not empty
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData3)),
                    'Token is not empty');
        
        //Check the max_views argument is required
        $badData4 = $goodData;
        unset($badData3['max_views']);
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData4)),
                            'Save: Field max_views is required');
        
        //Check no data has been inserted
        $this->assertEquals($nbRecords, $this->Links->find('all')->count(),
                      'Bad data has not been inserted');        
        
        //Check good data can be inserted
        $this->assertNotFalse($this->Links->save($this->Links->newEntity($goodData)),
                       'Good data can be inserted');
        $this->assertEquals($nbRecords + 1, $this->Links->find('all')->count(),
                        'A new record is in DB');        
        //And the data inserted is ok
        $data = $goodData;
        unset($data['token']);
        $this->assertArraySubset($data, 
                          $this->Links->find('all')
                                      ->where(['Links.title =' => $goodData['title']])
                                      ->toArray()[0]->toArray(),
                           'The inserted data corresponds to what we expect');
        
        //Check token has to be unique and its cached by data base
        $data4 = $goodData;
        unset($goodData['token']);
        $data4['title'] = $data4['title'] . date('');
        $data4['content'] = $data4['content'] . date('');        
        $this->Links->save($this->Links->newEntity($data4));
        $result = $this->Links->find("all")
                              ->where(['Links.title =' => $goodData['title']])
                              ->toArray();
        $this->assertNotEquals($result[0]->token, $result[1]->token,
                                'Two similar links do not have same tokn');
    }
}
