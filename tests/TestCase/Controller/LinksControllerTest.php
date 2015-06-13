<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LinksController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
/**
 * App\Controller\LinksController Test Case
 * @group Unit 
 * @group Controller
 */
class LinksControllerTest extends IntegrationTestCase
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
     * Data which respect the model constraints
     * @var array 
     */
    private $goodData = [
            'title' => 'Heisenberg',
            'content' => 'Walter Hartwell « Walt » White.',
            'token' => 'Say my name',
            'max_views' => 1
    ];
    
    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        // Check we have the home page
        $this->get('/');
        $this->assertResponseOk();
    }
    
    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {        
        $this->get('/1');        
        $this->assertResponseError('Links is not accessbile by its id');
        // First fixture's titles
        $this->get('/a1d0c6e83f027327d8461063f4ac58a6');
        $this->assertResponseContains('The link you try to access has a maximum views component');
         
        //TODO: mock an ajax request to check the link with max_views can be seen
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->get('/a1d0c6e83f027327d8461063f4ac58a6');
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
        unset( $_SERVER['HTTP_X_REQUESTED_WITH']);
        //A random token throw 404
        $this->get('/6063f4ac58a6a1d7383f02d10c6e2874');
        $this->assertResponseError('A random token throw 404');
        
        //This link has 1 view left
        $this->get('/6063f4ac58a6a1d7383f02d10c6e2874');
        $this->get('/6063f4ac58a6a1d7383f02d10c6e2874');
        $this->assertResponseError('Dead link throw an error');
    }

    /**
     * Test that the views counter increase when the page is consulted
     * 
     * @return void
     */
    public function testViewIncreaseCounter()
    {
        $links = TableRegistry::get('Links');
        $linkBefore = $links->findByToken('a1d0c6e83f027327d8461063f4ac58a6')->first();
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $this->get('/a1d0c6e83f027327d8461063f4ac58a6');
        unset( $_SERVER['HTTP_X_REQUESTED_WITH']);
        $linksAfter = $links->findByToken('a1d0c6e83f027327d8461063f4ac58a6')->first();
        $this->assertEquals($linkBefore->views + 1, $linksAfter->views);
    }
    
    public function testDeleteByTime() {
          // Fixate time. Look in the fixture 4 with title 'No max_views'
        $now = new Time('1935-11-07 18:38:00');
        Time::setTestNow($now);
        $this->get('/6c6e83f027327d846103f4ac58a6a1d0');
        $now = new Time('1955-11-10 6:38:01');
        Time::setTestNow($now);
        $this->get('/6c6e83f027327d846103f4ac58a6a1d0');
        $this->assertResponseError('Time limit involve link deletion');
    }
    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        //add action is not accessible from get
        $this->get("/add");
        $this->assertResponseError();
        // Add new data using POST method
        $data = $this->goodData;
        $this->post('/add', $data);        
        $this->assertResponseSuccess();

        // Check if the data has been inserted in database
        $links = TableRegistry::get('Links');
        $query = $links->find()->where(['title' => $data['title']]);       
        $this->assertEquals(1, $query->count(), 'A good link is added in DB');
        
        //The rendered template is an ajax one
        $this->assertTemplate('ajax/url');
        //The generated token is present in the response
        $this->assertResponseContains( $query->first()->token);        
        
        //Check controller set a flash message if link cannot be saved
        $badData = $data;
        $badData['content'] = '';
        $this->post('/add', $badData);
        $this->assertSession('The link could not be saved. Please, try again.',
                             'Flash.flash.message');
        $this->checkTokenGeneration();
                
    }

    private function checkTokenGeneration() {
        $goodData = $this->goodData;
        $links = TableRegistry::get('Links');
        
        //token is 32 bytes long (generated with md5)
        $this->post('/add', $goodData);
        $query = $links->find()->where(['title' => $goodData['title']]);
        // Execute the query        
        $tokenQuery1 = $query->first()->token;        
        $this->assertEquals(32,  strlen($tokenQuery1),'Tokens are 32 bytes long');
        
        //Two links with same information (title, content) have different token        
        $this->post('/add', $goodData);
        $this->assertResponseSuccess('Similar link can be added');
        // /!\ seems that we need to rebind query... 
        $query2 = $links->find()->where(['title' => $goodData['title']]);
        $result = $query2->all()->toArray();
        $this->assertNotEquals($result[0]->token, $result[1]->token,
                'Two similar links do not have the same tokens');
        
        //Token is not generated from id avoiding a brute force attack        
        $this->assertStringNotMatchesFormat($result[0]->token, md5($result[0]->id),
                'The token is not generated from the link id');
    }
    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        // Create new data
        $data = [
            'title' => 'Better call Saul',
            'content' => 'This is not Walter Hartwell « Walt » White.'            
        ];
        // Get link from first fixture
        $this->post('/edit/1', $data);
        $this->assertResponseSuccess();

        // Check if the data has been modified in database
        $links = TableRegistry::get('Links');
        $query = $links->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());
        
        //Test a flash message is set if something is wrong:                
        $badData = $data;
        $badData['content'] = '';
        $this->post('/edit/1', $badData);
        $this->assertSession('The link could not be saved. Please, try again.',
                             'Flash.flash.message');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        // Get link from first fixture
        $links = TableRegistry::get('Links');        
        $data = $links->get(1);
        
        // Delete this one
        $this->post('/delete/1');        
        $this->assertResponseSuccess();
        
        // Check if the data has been modified in database
        $query = $links->find()->where(['title' => $data['title']]);
        $this->assertEquals(0, $query->count());
        
        //TODO: check a flash message is set if something is wrong        
    }
}
