<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use App\Mailer\UserMailer;

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
            'confirm_password' => 'dummy_password',
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
        // Remove mailer for unit tests
        $this->Users->eventManager()->off('Model.afterSave', UserMailer::getInstance());
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
        $this->assertEquals(1, $this->Users->hasField('default_threshold'));
        $this->assertEquals(1, $this->Users->hasField('subscribe_notifications'));
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
        unset($data['confirm_password']);
        $dbUser = $this->Users->find('all')
                                      ->where(['Users.username =' => $goodData['username']])->toArray()[0];
        $userArray = $dbUser->toArray();
        $this->assertArraySubset($data,
                                $userArray,
                           'The inserted data corresponds to what we expect');
        $this->assertNotEquals($userArray['password'], $goodData['password'], 'Password has been hashed');

        $this->assertTrue($dbUser->subscribe_notifications, 'By default a registered user subscribe to notification');
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

    public function testChangeSubscribeNotifications()
    {
        $goodData = $this->goodData;
        $user = $this->Users->newEntity($goodData);
        $this->assertNotFalse($this->Users->save($user), 'Saving user');
        $user->subscribe_notifications = false;
        $this->assertNotFalse($this->Users->save($user), "Saving user after changing the subscribe_notifications field");
        $userAfterSave = $this->Users->get($user->id);
        $this->assertFalse($userAfterSave->subscribe_notifications, "Subscribe notifications has been changed");
    }

    public function testErrorsSubscribeNotifications()
    {
        $goodData = $this->goodData;
        $goodData["subscribe_notifications"] = "blabalba";
        $user = $this->Users->newEntity($goodData);
        $res = $this->Users->save($user);
        $this->assertFalse($res, "Non boolean data are not allowed for subscribe_notifications field");

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
     * Test password confirmation when user create its account
     */
    public function testPasswordConfirmCreate()
    {
        $badData = $this->goodData;
        unset($badData['confirm_password']);
        $user = $this->Users->newEntity($badData);
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Password need a confirmation');

        $user = $this->Users->newEntity();
        $badData = $this->goodData;
        $badData['confirm_password'] = "This is not the confirm";
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Password and confirmation must be equal');

        $user = $this->Users->newEntity();
        $goodData = $this->goodData;
        $goodData['password'] = "confirm";
        $goodData['confirm_password'] = "confirm";
        $user = $this->Users->patchEntity($user, $goodData);
        $this->assertNotFalse($this->Users->save($user), 'Password and confirmation must be equal');
    }

    /**
     * Test password confirmation when user modify its informations
     */
    public function testPasswordConfirmModify()
    {
        $goodData = $this->goodData;
        // User should be able to modify its information without giving password
        unset($goodData['password']);
        unset($goodData["confirm_password"]);

        $user = $this->Users->get(1);
        $goodData["email"] = "fake@email.com";
        $this->Users->patchEntity($user, $goodData);
        $this->assertNotFalse($this->Users->save($user));

        $badData =$goodData;
        $badData['password'] = "noConfirm";
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), "If user redefined the password, he must confirm it");

        $badData =$goodData;
        $badData['password'] = "";
        $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), "Password must no be empty");

        // User should be able to modify its information  giving good password confirmation
        $user = $this->Users->get(1);
        $goodData = $this->goodData;
        $goodData["password"] = "thisIsAPass";
        $goodData["confirm_password"] = "thisIsAPass";
        $this->Users->patchEntity($user, $goodData);
        $this->assertNotFalse($this->Users->save($user), "Good confirm save the user");
    }

    public function testErrorsLifeThreshold()
    {
        $badData = $this->goodData;
        $user = $this->Users->newEntity();
        $badData['default_threshold'] = -1;
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'Negative life threshold is not allowed');

        $badData['default_threshold'] = 101;
        $user = $this->Users->patchEntity($user, $badData);
        $this->assertFalse($this->Users->save($user), 'More than 100 life threshold not allowed');
    }

    public function testFindNeedMailAlert()
    {
        $users = $this->Users->find('needMailAlert')->all();
        $this->assertEquals(2, count($users), 'Only users with a email adress defined are retrieved');


        // Artificialy unvalidate emails of a user
        $user1 = $this->Users->get(1);
        $user1->email_validated = false;
        $this->Users->save($user1);
        $users = $this->Users->find('needMailAlert')->all();
        $this->assertEquals(1, count($users), "Users need to have an email validated to retrieve mail alert");

        // Test users can disable their notifications
        $user2 = $this->Users->get(3);
        $user2->subscribe_notifications = false;
        $this->Users->save($user2);
        $users = $this->Users->find('needMailAlert')->all();
        $this->assertEmpty($users, "Users can disable their notifications alerts");
    }

    public function testEmailValidationRequired()
    {
        $goodData = $this->goodData;
        // Field initialization
        $user = $this->Users->newEntity($goodData);
        $user = $this->Users->save($user);
        $this->assertNotFalse($user, 'User can be saved');

        //Retrieve user to get default values
        $user = $this->Users->get($user->id);
        $this->assertFalse($user->email_validated, 'Email is not validated when user has just been registered');
        $validationLink = $user->email_validation_link;
        $this->assertNotEmpty($validationLink, 'Validation link is set');

        // Manually validate email
        $user->email_validated = true;
        $user = $this->Users->patchEntity($user, $goodData);
        $this->Users->save($user);

        // Change an other field
        $user->username = "anotherusername";
        $user = $this->Users->patchEntity($user, $goodData);
        $this->Users->save($user);
        $user = $this->Users->get($user->id);
        $this->assertTrue($user->email_validated, "Email validation is not changed if an other field is changed");

        // Test email modification implies reset of the validated flag and url
        $goodData["email"] = "an-email@modified.org";
        $user = $this->Users->patchEntity($user, $goodData);
        $user = $this->Users->save($user);
        $this->assertFalse($user->email_validated, 'Email validation is reset when email is changed');
        $this->assertNotEmpty($user->email_validation_link, "Email validation link reinitialization is not empty");
        $this->assertNotEquals($validationLink, $user->email_validated_link, 'Link email validation is changed');
    }
}
