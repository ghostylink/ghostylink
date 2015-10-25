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
        'app.users',
        'app.links',
        'app.alert_parameters'
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
     * Test basic case when all fields are specified.
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
        //unset password as it will be hashed
        unset($data['password']);
        $dbUser = $this->Users->find('all')
                                      ->where(['Users.username =' => $goodData['username']])
                                      ->toArray()[0]->toArray();
        $this->assertArraySubset($data,
                                $dbUser,
                           'The inserted data corresponds to what we expect');
        $this->assertNotEquals($dbUser['password'], $goodData['password'], 'Password has been hashed');

    }

    public function testEmailNotRequired()
    {
        $goodData = $this->goodData;
        unset($goodData['email']);
        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $goodData);
        $this->assertNotFalse($this->Users->save($user),
                                'Email is not required');
        $goodData = $this->goodData;
        $goodData['username'] = 'AnOtherUsername';
        $goodData['email'] = '';
        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $goodData);

        $this->assertNotFalse($this->Users->save($user),
                                'Email can be left empty');

        $goodData = $this->goodData;
        $goodData['username'] = 'AnOtherUsername2';
        $goodData['email'] = '';
        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $goodData);

        $this->assertNotFalse($this->Users->save($user),
                                'Null Email is not affected by unicity constraint');
    }

    public function testErrorsUsername()
    {
        $badData = $this->goodData;
        $user = $this->Users->newEntity();

        $badData['username'] = '';
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Empty username implies non saving');

        unset($badData['username']);
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'No username implies non saving');

        $badData = $this->goodData;
        $badData['username'] = 'a';
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Short username implies non saving');

        $badData = $this->goodData;
        $badData['username'] = str_repeat('a', 21);
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Long username implies non saving');

        $badData = $this->goodData;
        $badData['username'] = 'user1';
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Username must be unique');
    }
    /**
     * Test errors on mail are catched
     */
    public function testErrorsMail()
    {
        $goodData = $this->goodData;
        $badData = $goodData;
        $badData['email'] = 'whalter';

        $user = $this->Users->newEntity();
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Bad email implies non saving');

        $badData['email'] = 'user1@ghostylink.org';
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Email must be unique');
    }

    /**
     * Test errors on password
     */
    public function testErrorsPassword()
    {
        $badData = $this->goodData;
        $user = $this->Users->newEntity();

        $badData['password'] = '';
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Empty password implies non saving');

        $badData = $this->goodData;
        unset($badData['password']);
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'No password implies non saving');

        $badData = $this->goodData;
        $badData['password'] = 'abc';
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Too small password implies non saving');

        $badData = $this->goodData;
        $badData['password'] = str_repeat('a', 21);
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Too lonog password implies non saving');
    }

    /**
     * @group Develop
     */
    public function testFindNeedMailAlert()
    {
        $users = $this->Users->find('needMailAlert')->all();
        $this->assertEquals(2, count($users), 'Only users with a email adress defined are retrieve');
    }
}
