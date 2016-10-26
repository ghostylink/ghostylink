<?php
namespace App\Test\TestCase\Model\Table;

use App\Mailer\LinkMailer;
use App\Shell\MailerShell;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Core\Configure;

/**
 * App\Model\Entity\Link Test Case
 * @group Unit
 * @group Entity
 * @group Model
 */
class MailerShellTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'Links' => 'app.links',
        'Users' => 'app.users',
        'AlertParameters' => 'app.alert_parameters'
    ];

    /**
     *
     * @var \App\Shell\MailerShel
     */
    private $shell = null;

    /**
     *
     * @var \App\Model\Table\LinksTable
     */
    private $Links = null;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Links') ? [] : ['className' => 'App\Model\Table\LinksTable'];
        $this->Links = TableRegistry::get('Links', $config);
        $this->shell = new MailerShell();
        $this->shell->initialize();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Links);

        parent::tearDown();
    }

    public function testAlerts()
    {
        // mail alerts are sent ...
        $shellMock = $this->getShellMock($this->atLeastOnce());
        $shellMock->alerts();
        
        // ... only one time
        $shellMock = $this->getShellMock($this->never());
        $shellMock->alerts();
    }

    public function testAlertsDisable()
    {
        Configure::write(["EmailTransport.default.host" => null]);

        // Disable by configuration mail sending
        $shellMock = $this->getShellMock($this->never());
        $shellMock->alerts();
    }

    private function getMailerMock($callMatcher)
    {
        $mailerMock =  $this->getMockBuilder(LinkMailer::class)
                            ->setMethods(array('send'))
                            ->getMock();
        $mailerMock->expects($callMatcher)->method('send');
        return $mailerMock;
    }

    private function getShellMock($callMatcher)
    {
        $mailerMock = $this->getMailerMock($callMatcher);
        $shellMock = $this->getMockBuilder(MailerShell::class)
                          ->setMethods(array('getMailer'))
                          ->getMock();

        $shellMock->expects($callMatcher)
                  ->method('getMailer')
                  ->willReturn($mailerMock);
        $shellMock->initialize();
        return $shellMock;
    }
}
