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
        $this->assertResponseOk();
        // First fixture's titles
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
        $data = [
            'title' => 'Heisenberg',
            'content' => 'Walter Hartwell « Walt » White.',
        ];
        $this->post('/links/add', $data);
        $this->assertResponseSuccess();

        // Check if the data has been inserted in database
        $links = TableRegistry::get('Links');
        $query = $links->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());
        
        //Check controller set a flash message if link cannot be saved
        $badData = $data;
        $badData['content'] = '';
        $this->post('/links/add', $badData);
        $this->assertSession('The link could not be saved. Please, try again.',
                             'Flash.flash.message');
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
