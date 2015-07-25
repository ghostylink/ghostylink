<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\UsersController Test Case
 * @group Unit 
 * @group Controller
 */
class UsersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    
    public function testLogin()
    {        
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
    }
    
    public function testLogout()
    {
        $this->_authenticateUser(0);
        $this->get("/login");        
        $this->assertSession('user1', 'Auth.User.username');
        $this->get("/logout");
        $this->assertSession('', 'Auth.User.username');
    }
    
    public function _authenticateUser($fixtureIndex)
    {
        $userArray = $this->fixtureManager->loaded()['app.users']
                          ->records[$fixtureIndex];
        if(isset($userArray['password'])) {
            unset($userArray['password']);
        }
        // Set session data
        $this->session([
            'Auth' => [
                'User' => $userArray
            ]
        ]);       
    }
}
