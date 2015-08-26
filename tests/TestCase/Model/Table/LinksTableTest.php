<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LinksTable Test Case
 * @group Unit
 * @group Table
 * @group Model
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
            'max_views' => 8,
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
        $this->assertEquals(1, $this->Links->hasField('views'));
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
        $badData = $this->goodData;
        $badData['title'] = 'titleTestMaxViewsErrors0';
        $badData['max_views'] = 0;
        $entity = $this->Links->newEntity($badData);
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                            'Save: Field max_views is > 0');
        $this->assertArrayHasKey('max_views', $entity->errors());
        
        $badData['max_views'] = -1;
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                            'Save: Field max_views cannot be negative');

        $badData['max_views'] = 1001;
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                            'Save: Field max_views cannot be more than 1000');

        $badData = $this->goodData;
        $badData['max_views'] = 'You shall not pass!';
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                            'Save: Field max_views is integer');
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
        $link = $this->Links->newEntity();
        $link = $this->Links->patchEntity($link, $goodData);
        $this->assertNotFalse($this->Links->save($link),
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
        $link = $this->Links->newEntity();
        $link = $this->Links->patchEntity($link, $goodData);
        $this->Links->save($link);
        $insertedData = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray()[0];
        // FIXME Pass those tests
        //$this->assertNotNull($insertedData->views,'views is not null');
        //$this->assertEquals(0,$insertedData->views,'views counter is set to 0');
    }

    public function testIncreaseViews() {
        $goodData = $this->goodData;
        $goodData['title'] = 'titleDeleteLink';
        $goodData['max_views'] = 3;
        $link = $this->Links->newEntity($goodData);
        $this->Links->save($link);
        $this->Links->increaseLife($link);
        $this->Links->save($link);
        $linkDB = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray()[0];
        $this->assertEquals(1,$linkDB->views,'views counter is set to 1');
        $this->Links->increaseLife($link);
        $this->Links->increaseLife($link);
        $this->Links->save($link);
        $linkDB = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray()[0];
        $this->assertEquals(3,$linkDB->views,'views counter is at 3 before delete');
        $this->Links->increaseLife($link);
        $linkDB = $this->Links->find('all')
                                ->where(['Links.title =' => $goodData['title']])
                                ->toArray();
        $this->assertEmpty($linkDB, 'Link has been deleted');
    }

    /**
     * Check if we cannot disable a link already disabled
     *
     * @return void
     */
    public function testDisableLink() {
        $disabledLink = $this->Links->find('all')
                              ->where(['title' => 'Disabled link'])
                              ->toArray()[0];
        $result = $this->Links->disable($disabledLink);
        $this->assertEquals(false, $result);
    }

    /**
     * Check if we cannot enable a link already enabled
     *
     * @return void
     */
    public function testEnableLink() {
        $enabledLink = $this->Links->find('all')
                              ->where(['title' => 'Enabled link'])
                              ->toArray()[0];
        $result = $this->Links->enable($enabledLink);
        $this->assertEquals(false, $result);
    }
}
