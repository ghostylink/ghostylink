<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LinksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\I18n\Time;

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
        'Links' => 'app.links',
        "AlertParamters" => 'app.alert_parameters'
    ];

    /**
     * The save function should not return false using this data
     * @var array
     */
    private  $goodData = [
            'title' => 'I am not in danger ...',
            'content' => 'I am the danger !',
            'token' => 'Say my name',
            'private_token' => 'You’re an insane, degenerate piece of filth, and you deserve to die.',
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
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\AlertParameterTable'];
        $this->AlertParameters = TableRegistry::get('AlertParameters', $config);
        $this->goodData['private_token'] = uniqid();
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
        $this->assertEquals(1, $this->Links->hasField('private_token'));
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
     * Test errors on private token
     */
    public function testPrivateTokenErrors() {
        $badData = $this->goodData;
        unset($badData['private_token']);
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)), 'Private token is required');

        $badData['private_token'] = '';
        $this->assertFalse($this->Links->save($this->Links->newEntity($badData)),
                    'Private token is not empty');

         //Check private tokens are unique
        $goodData = $this->goodData;
        $goodData['title'] = 'titleTestPrivateTokenErrors';
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
        // Tokens are randomly generated, cannot be checked in this way
        unset($data['token']);
        unset($data['private_token']);
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
        // FIXME Pass this test
        //$this->assertNotNull($insertedData->views,'views is not null');
        $this->assertEquals(0, $insertedData->views,'views counter is set to 0');
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

    public function testFinderRangeLifeErrors() {
        // Test min_life and max_life are required
        $this->setExpectedException('BadFunctionCallException');
        $this->Links->find('rangeLife');
    }

    public function testFinderRangeLife() {
        // Check that the retrieved links have their life_percentage between the specified values
        $MIN_LIFE = 90;
        $MAX_LIFE = 96;

        $array = $this->Links->find('rangeLife', ['min_life' => $MIN_LIFE , 'max_life' => $MAX_LIFE])
                                            ->toArray();
        $this->assertEquals(count($array), 3, 'Exactly 1 links is retrieved by views');
        foreach ($array as $value) {
            $this->assertGreaterThanOrEqual($MIN_LIFE, $value->life_percentage);
            $this->assertLessThanOrEqual($MAX_LIFE, $value->life_percentage);
        }

        // Test with death_time
        $MIN_LIFE = 0;
        $MAX_LIFE = 20;
        $link = $this->Links->newEntity();
        $goodData = $this->goodData;
        $goodData['title'] = 'Title finder range life by death_time';
        $link = $this->Links->patchEntity($link, $goodData);
        //As setting current date will not impact mysql server, we need to insert manualy a new link
        $link->death_time = $now = (new Time())->addHour();
        $this->Links->save($link);
        $array = $this->Links->find('rangeLife', ['min_life' => $MIN_LIFE , 'max_life' => $MAX_LIFE])
                                            ->where(['title' => 'Title finder range life by death_time'])->toArray();
        $this->assertEquals(1, count($array), 'Exactly 1 links is retrieved by death_time');
        $this->assertEquals($array[0]->title, 'Title finder range life by death_time', 'The retrieved link is the on expected');

        $MIN_LIFE = 5;
        $array = $this->Links->find('rangeLife', ['min_life' => $MIN_LIFE , 'max_life' => $MAX_LIFE])
                                            ->where(['title' => 'Title finder range life by death_time'])->toArray();
        $this->assertEquals(0,  count($array), '0 links is retrieved by death_time');
    }

    public function testFinderHistory() {
        // Check that the retrieved links have their life_percentage between the specified values
        $MIN_LIFE = 90;
        $MAX_LIFE = 96;

        $array = $this->Links->find('history', ['min_life' => $MIN_LIFE , 'max_life' => $MAX_LIFE, 'user_id' => 1])
                                            ->toArray();
        $this->assertEquals(count($array), 1, 'Exactly 1 links is retrieved by views');

        $array = $this->Links->find('history', ['title' => 'I do not exist',
                                                                      'min_life' => $MIN_LIFE ,
                                                                      'max_life' => $MAX_LIFE, 'user_id' => 1])
                                            ->toArray();
        $this->assertEquals(count($array), 0, 'Test filter on title');

        $array = $this->Links->find('history', ['status' => 1,
                                                                      'min_life' => $MIN_LIFE ,
                                                                      'max_life' => $MAX_LIFE, 'user_id' => 1])
                                            ->toArray();
        $this->assertEquals(count($array), 1, 'Test filter on status');
    }

    public function testFinderHistoryError() {
        $MIN_LIFE = 90;
        $MAX_LIFE = 96;
        $this->setExpectedException('BadFunctionCallException');
        $array = $this->Links->find('history', ['min_life' => $MIN_LIFE , 'max_life' => $MAX_LIFE]);
    }

    public function testFinderNeedMailAlert() {
        $array = $this->Links->find('needMailAlert')->all();
        $this->assertEquals(2, count($array),  'Test filter on mail alert');

         // Change the life threshold of one of the link to a higher value then the life_percentage;
        $linkToChange = $array->first();
        $alertToChange = $this->AlertParameters->find('all')->where(["link_id" => $linkToChange->id])->first();
        $alertToChange->life_threshold = $linkToChange->life_percentage + 5;
        $this->AlertParameters->save($alertToChange);
         $array = $this->Links->find('needMailAlert')->contain('AlertParameters')->all();
         $this->assertEquals(1, $array->count(),
                                                        'Need mail alert finder take in account the alert parameter life threshold');

         // Disable notifications for the remaining link
         $linkToChange = $array->first();
         $alert = $linkToChange->alert_parameter;
         $alert->subscribe_notifications = false;
         $this->AlertParameters->save($alert);

         $array = $this->Links->find('needMailAlert')->contain('AlertParameters')->all();
         $this->assertEmpty($array, "Finder only retrieved link which have subscribe_notifications set to true");
    }

    public function testAddWithAlertParameter()
    {
        $data = $this->goodData;
        $data["content"] = "testAlertParameter";
        $data["alert_parameter"] = [
            'life_threshold' => 42
        ];
        debug($data);
        $link = $this->Links->newEntity($data, [
            'associated' => ['AlertParameters']
        ]);
        $this->assertNotFalse($this->Links->save($link), "Link with alert paramter can be saved");
        $l = $this->Links->findByContent("testAlertParameter")->contain("AlertParameters")->first();
        $this->assertEquals(
            42,
            $l->alert_parameter->life_threshold,
            "Life threshold is saved"
        );
    }
}
