<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AlertParametersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AlertParametersTable Test Case
 */
class AlertParametersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.links',
        'app.alert_parameters'
    ];

    public $goodData = [
        'link_id' => 2,
        'type' =>'email',
        'sending_status' => 0,
        'life_threshold' => 75
    ];
    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AlertParameters') ? [] : ['className' => 'App\Model\Table\AlertParametersTable'];
        $this->AlertParameters = TableRegistry::get('AlertParameters', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AlertParameters);

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
        $this->assertNotEmpty($this->AlertParameters);
        $this->assertEquals("alert_parameters", $this->AlertParameters->table());
        $this->assertEquals("id", $this->AlertParameters->primaryKey());
        $this->assertEquals(1, $this->AlertParameters->hasField('life_threshold'));
        $this->assertEquals(1, $this->AlertParameters->hasField('type'));
        $this->assertEquals(1, $this->AlertParameters->hasField('sending_status'));
        $this->assertEquals(1, $this->AlertParameters->hasField('link_id'));
        $this->assertEquals(1, $this->AlertParameters->hasField('subscribe_notifications'));
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $goodData = $this->goodData;
        $alertParameters = $this->AlertParameters->newEntity();
        $alertParameters = $this->AlertParameters->patchEntity($alertParameters, $goodData);
        $this->assertNotFalse($this->AlertParameters->save($alertParameters));
    }

    public function testValidationErrors()
    {
        $goodData = $this->goodData;

        //Test life_threshold between 0 and 100
        $goodData = $this->goodData;
        $goodData['life_threshold'] = -1;
        $alertParameters = $this->AlertParameters->newEntity($goodData);
        $alertParameters = $this->AlertParameters->patchEntity($alertParameters, $goodData);
        $this->assertFalse($this->AlertParameters->save($alertParameters));
        $goodData['life_threshold'] = 101;
        $alertParameters = $this->AlertParameters->patchEntity($alertParameters, $goodData);
        $this->assertFalse($this->AlertParameters->save($alertParameters));

        // Test only email is authorized
        // TODO: for version 2.0, remove rss test
        $goodData = $this->goodData;
        $goodData['type'] = 'rss';
        $alertParameters = $this->AlertParameters->newEntity($goodData);
        $alertParameters = $this->AlertParameters->patchEntity($alertParameters, $goodData);
        $this->assertFalse($this->AlertParameters->save($alertParameters));
    }

    public function testErrorOnSubscribeNotifications()
    {
        $goodData = $this->goodData;
        $goodData["subscribe_notifications"] = "notaboolean";
        $alertParameters = $this->AlertParameters->newEntity($goodData);
        $this->assertFalse($this->AlertParameters->save($alertParameters));
    }
    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $goodData = $this->goodData;
        //Test the foreign key presence
        $goodData['link_id'] = 8000;
        $alertParameters = $this->AlertParameters->newEntity($goodData);
        $alertParameters = $this->AlertParameters->newEntity($goodData);
        $this->assertFalse($this->AlertParameters->save($alertParameters));
    }
}
