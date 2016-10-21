<?php
namespace App\Test\TestCase\Controller;

use App\Controller\LinksController;
use Cake\TestSuite\IntegrationTestCase;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Mailer\Mailer;

/**
 * App\Controller\LinksController Test Case
 * @group Unit
 * @group Controller
 */
class UserMailerTest extends IntegrationTestCase
{

    /**
     *
     * @var \App\Model\Table\UsersTable
     */
    private $Users;

    private $goodData = [
        'username' => 'Walter White',
        'password' => 'dummy_password',
        'confirm_password' => 'dummy_password',
        'email' => 'walter.white@pollos-hermanos.us'
    ];
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Users' => 'app.users'
    ];


    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * Test that configuration is taken in account for disabling email sending
     *
     * @return void
     */
    public function testMailingDisabled()
    {
        // Check email confirm is disabled when no email host is provided
        Configure::write(["EmailTransport.default.host" => null]);

        // ... at creation
        $user = new \App\Model\Entity\User();
        $user = $this->Users->patchEntity($user, $this->goodData);
        $mock = $this->getMockBuilder('\App\Mailer\UserMailer')
                     ->setMethods(array('send'))
                     ->getMock();
        $mock->expects($this->never())
             ->method('send')
             ->with();
        $this->Users->save($user);

        //... at update
        $user = $this->Users->get(1);
        $user = $this->Users->patchEntity($user, ["email" => "email@modified.fr"]);
        $mock = $this->getMockBuilder('\App\Mailer\UserMailer')
                     ->setMethods(array('send'))
                     ->getMock();
        $mock->expects($this->never())
             ->method('send')
             ->with();
        $this->Users->save($user);
    }

    public function testMailSending()
    {
        Configure::write(["EmailTransport.default.host" => "http://localhost"]);
        
        // ... at creation
        $user = new \App\Model\Entity\User();
        $user = $this->Users->patchEntity($user, $this->goodData);
        
        $mock = $this->getMockBuilder('\App\Mailer\UserMailer')
                     ->setMethods(array('send'))
                     ->getMock();
        
        $mock->expects($this->once())
             ->method('send')
             ->with("emailConfirmation", [$user]);
        $options = new \ArrayObject();
        $event = new \Cake\Event\Event("dummyEvent");
        
        $mock->onRegistration($event, $user, $options);

        //... at update
        $user = $this->Users->get(1);
        $user = $this->Users->patchEntity($user, ["email" => "email@modified.fr"]);

        $mock = $this->getMockBuilder('\App\Mailer\UserMailer')
                     ->setMethods(array('send'))
                     ->getMock();

        $mock->expects($this->once())
             ->method('send')
             ->with("emailConfirmation", [$user])
             ->willReturn(null);
        $mock->onRegistration($event, $user, $options);
    }
}
