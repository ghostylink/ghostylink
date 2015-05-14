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
     * Test errors on title are catched
     * @return void
     */
    public function testTitleErrors() {        
        //Check the title is required
        $badData = $this->goodData;
        $badData['title'] = '';        
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)));
    }
    
    /**
     * Test errors on content are catched
     * @return void
     */
    public function testContentErrors() {       
        //Check the content is required
        $badData = $this->goodData;
        $badData['content'] = '';        
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData))); 
    }
    
    /**
     * Test errors on token are catched
     * @return void
     */
    public function testTokenErrors() {
        //Check the token is required ...
        $badData = $this->goodData;
        unset($badData['token']);
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)), 
                    'Token is required');
        
        //.. and not empty
        $badData['token'] = '';        
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                    'Token is not empty');
        
        $goodData = $this->goodData;
        $goodData['title'] = 'titleTestTokenErrors';
        
        //Check tokens are unique
        $this->Links->save($this->Links->newEntity($goodData));
        $this->Links->save($this->Links->newEntity($goodData));
        $result = $this->Links->find("all")
                              ->where(['Links.title =' => $goodData['title']])
                              ->toArray();
        $this->assertNotEquals($result[0]->token, $result[1]->token,
                                'Two similar links do not have same tokn');
    }
    
    /**
     * Test errors on max_views are catched
     * @return void
     */
    public function testMaxViewsErrors() {
        //Check the max_views argument is required
        $badData = $this->goodData;
        $badData['title'] = 'titleTestMaxViewsErrors';         
        unset($badData['max_views']);                               
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                            'Save: Field max_views is required'); 
    }
    
    /**
     * Test data consistency when no data is inserted
     * @return void
     */
    public function testValidationFail() {
        $nbRecords = $this->Links->find('all')->count();                                          
        //Build bad data with an empty token
        $badData = $this->goodData;
        $badData['title'] = '';
        
        $this->Links->save($this->Links->newEntity($badData));
        //Check no data has been inserted        
        $this->assertEquals($nbRecords, $this->Links->find('all')->count(),
                      'Bad data has not been inserted');        
    }
    
    /**
     * Test data insertion when everything is ok
     *
     * @return void
     */
    public function testValidation()
    {               
        $nbRecords = $this->Links->find('all')->count();                                                     
        
        $goodData = $this->goodData;
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
       
    }
    
    /**
     * Check default values for non required fields are initialized
     */
    public function testValidationDefault() {
        $goodData = $this->goodData;
        $goodData['title'] = 'titletestValidationDefault';
        $this->Links->save($this->Links->newEntity($goodData));
        $insertedData = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray()[0];
        $this->assertEquals(0,$insertedData->views,'views counter is set to 0');        
    }
    
    public function testIncreaseViews() {
        $goodData = $this->goodData;
        $goodData['title'] = 'titleDeleteLink';
        $goodData['max_views'] = 3;
        $link = $this->Links->newEntity($goodData);
        $this->Links->save($link);
        $this->Links->increaseViews($link);
        $this->Links->save($link);
        $linkDB = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray()[0];        
        $this->assertEquals(1,$linkDB->views,'views counter is set to 1');
        $this->Links->increaseViews($link);
        $this->Links->increaseViews($link);
        $this->Links->save($link);
        $linkDB = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray()[0];
        $this->assertEquals(3,$linkDB->views,'views counter is at 3 before delete');
        $this->Links->increaseViews($link);
        $linkDB = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray();
        $this->assertEmpty($linkDB, 'Link has been deleted');
    }
}
