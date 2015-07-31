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

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
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
    public function testEdit()
    {
        $this->_authenticateUser(0);
        $this->get('/me/edit');
        $this->assertResponseOk();
        
        $newData = ['username' => 'newusername'];
        
        $this->post('/me/edit', $newData);
        $this->assertResponseCode(302);
        
        //TODO: check the session value has been updated
        
        $users = TableRegistry::get('Users');
        $query = $users->find()->where(['username' => $newData['username']]);       
        $this->assertEquals(1, $query->count(), 'User has been saved');
        
        $badData = ['username' => 'a'];
        $this->post('/me/edit', $newData);
                
        $query = $users->find()->where(['username' => $badData['username']]);       
        $this->assertEquals(0, $query->count(), 'User has not been saved');
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
