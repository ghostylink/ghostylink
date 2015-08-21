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
class UsersControllerTest extends IntegrationTestCase {

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
        'email' => 'crystal@alabama.us'
    ];

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

        $this->post('/me/edit', $newData);
        $this->assertResponseCode(302);

        $this->assertSession('newusername', 'Auth.User.username');

        $users = TableRegistry::get('Users');
        $query = $users->find()->where(['username' => $newData['username']]);
        $this->assertEquals(1, $query->count(), 'User has been saved');

        $badData = ['username' => 'a'];
        $this->post('/me/edit', $newData);

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
        $this->post('/me/delete');
        //Impossible to delete account without having been loged in
        $this->assertResponseCode(302);
        $this->_authenticateUser(0);

        $users = TableRegistry::get('Users');
        $query = $users->find('all');
        $nbResult = $query->count();
        $this->post('/me/delete');
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
        $this->post('/login', $badData);
        $this->assertResponseContains('Username or password not valid');
        $this->post('/login', $goodData);
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

    public function _authenticateUser($fixtureIndex) {
        $userArray = $this->fixtureManager->loaded()['app.users']
                ->records[$fixtureIndex];
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
