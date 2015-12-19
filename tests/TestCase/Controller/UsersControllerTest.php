<?php

namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\UsersController Test Case
 * @group Unit
 * @group Controller
 */
class UsersControllerTest extends IntegrationTestCase
{

    private $csrf  =[null];

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.links'
    ];

    /**
     * Data which respect the model constraints
     * @var array
     */
    private $goodData = [
        'username' => 'Heisenberg',
        'password' => 'I am the danger',
        'confirm_password' => 'I am the danger',
        'email' => 'crystal@alabama.us'
    ];

    public function setUp() {
        $token = 'my-csrf-token';
        $this->cookie('csrfToken', $token);
        $this->goodData['_csrfToken'] = $token;
        $this->csrf ['_csrfToken'] = $token;
        parent::setUp();
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd() {
        //Good data can be inserted
        $data = $this->goodData;
        $this->post('/signup', $data);
        $this->assertResponseSuccess();

        //Bad data generates error
        $badData = $this->goodData;
        $badData['username'] = 'username';
        $this->post('/signup', $badData);
        $this->assertResponseContains('error');

        //TODO : prevent hacking test
        //add email_validated to true in sent data is ignored
        // same for email_validation link
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit() {
        $this->_authenticateUser(0);
        $this->get('/me/edit');
        $this->assertResponseOk();

        $newData = ['username' => 'newusername'];

        $this->post('/me/edit', array_merge($newData, $this->csrf));
        $this->assertResponseCode(302);

        $this->assertSession('newusername', 'Auth.User.username');

        $users = TableRegistry::get('Users');
        $query = $users->find()->where(['username' => $newData['username']]);
        $this->assertEquals(1, $query->count(), 'User has been saved');

        $badData = ['username' => 'a'];
        $this->post('/me/edit', array_merge($newData, $this->csrf));

        $query = $users->find()->where(['username' => $badData['username']]);
        $this->assertEquals(0, $query->count(), 'User has not been saved');
        $this->_logoutUser();
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete() {
        $this->post('/me/delete', $this->csrf);
        //Impossible to delete account without having been loged in
        $this->assertResponseCode(302);
        $this->_authenticateUser(0);

        $users = TableRegistry::get('Users');
        $query = $users->find('all');
        $nbResult = $query->count();
        $this->post('/me/delete', $this->csrf);
        $this->assertResponseCode(302);
        $users = TableRegistry::get('Users');
        $query = $users->find('all');
        $this->assertEquals($nbResult - 1, $query->count(), 'User has been deleted');
    }

    public function testLogin() {
        $this->get("/login");
        $this->assertResponseOk();
        // Add new data using POST method
        $goodData = [
            'username' => 'user1',
            'password' => 'user1user1',
        ];
        $badData = $goodData;
        $badData['password'] = 'surelyABadPassword';
        $this->post('/login', array_merge($badData, $this->csrf));
        $this->assertResponseContains('Username or password not valid');
        $this->post('/login', array_merge($goodData, $this->csrf));
        //A redirection ...
        $this->assertResponseCode(302);
        // ... then a page is displayed
        $this->assertResponseSuccess();
        $this->_logoutUser();
    }

    public function testLogout() {
        $this->_authenticateUser(0);
        $this->get("/login");
        $this->assertSession('user1', 'Auth.User.username');
        $this->get("/logout");
        $this->assertSession('', 'Auth.User.username');
        $this->_logoutUser();
    }

    public function testEmailValidation() {
        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->newEntities($this->goodData);
        $user = $usersTable->save($user);
        $this->assertNotFalse($user, 'User to validate email is saved');

        // Need a login user
        $this->get("/validate-email/" . $user->email_validation_link);
        $this->assertResponseCode(302);
        $user = $usersTable->get($user->id);
        $this->assertFalse($user->email_validated, 'Non logged user cannot validate its email');

        // Need to be the good user
        $this->_authenticateUser(0);
        $this->get("/validate-email/" . $user->email_validation_link);
        $this->assertResponseCode(302);
        $user = $usersTable->get($user->id);
        $this->assertFalse($user->email_validated, 'Bad logged user cannot validate its email');

        // When good user is logged in, email can be validated
        $this->assertResponseSuccess();
        $user = $usersTable->get($user->id);
        $this->assertTrue($user->email_validated, 'Good logged user can validate its email');
    }
    /**
     * Authenticate a user from a fixture index or from an Entity
     * @param mixte $user
     */
    public function _authenticateUser($user) {

        if ($user instanceof \Cake\ORM\Entity) {
            $userArray = $user->toArray();
        } else {
            $userArray = $this->fixtureManager->loaded()['app.users']
                ->records[$user];
        }
        if (isset($userArray['password'])) {
            unset($userArray['password']);
        }
        // Set session data
        $this->session([
            'Auth' => [
                'User' => $userArray
            ]
        ]);
    }

    public function _logoutUser() {
        $this->session([]);
    }

}
