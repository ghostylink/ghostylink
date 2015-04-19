<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LinksController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\LinksController Test Case
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
            'token' => 'Say my name'
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
        $this->get('/links/view/1');        
        $this->assertResponseError('Links is not accessbile by its id');
        // First fixture's titles
        $this->get('/links/view/a1d0c6e83f027327d8461063f4ac58a6');
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        // Add new data using POST method
        $data = $this->goodData;
        $this->post('/links/add', $data);
        $this->assertResponseSuccess();

        // Check if the data has been inserted in database
        $links = TableRegistry::get('Links');
        $query = $links->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count(), 'A good link is added in DB');
        
        //Check controller set a flash message if link cannot be saved
        $badData = $data;
        $badData['content'] = '';
        $this->post('/links/add', $badData);
        $this->assertSession('The link could not be saved. Please, try again.',
                             'Flash.flash.message');
        $this->checkTokenGeneration();
    }

    private function checkTokenGeneration() {
        $goodData = $this->goodData;
        $links = TableRegistry::get('Links');
        
        //token is 32 bytes long (generated with md5)
        $this->post('/links/add', $goodData);
        $query = $links->find()->where(['title' => $goodData['title']]);
        // Execute the query        
        $tokenQuery1 = $query->first()->token;        
        $this->assertEquals(32,  strlen($tokenQuery1),'Tokens are 32 bytes long');
        
        //Two links with same information (title, content) have different token        
        $this->post('/links/add', $goodData);
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
            'content' => 'This is not Walter Hartwell « Walt » White.',
        ];
        // Get link from first fixture
        $this->post('/links/edit/1', $data);
        $this->assertResponseSuccess();

        // Check if the data has been modified in database
        $links = TableRegistry::get('Links');
        $query = $links->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());
        
        //Test a flash message is set if something is wrong:                
        $badData = $data;
        $badData['content'] = '';
        $this->post('/links/edit/1', $badData);
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
        $this->post('/links/delete/1');
        $this->assertResponseSuccess();
        
        // Check if the data has been modified in database
        $query = $links->find()->where(['title' => $data['title']]);
        $this->assertEquals(0, $query->count());
        
        //TODO: check a flash message is set if something is wrong        
    }
}
