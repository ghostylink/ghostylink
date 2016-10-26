<?php
namespace App\Test\TestCase\Model\Table;

use App\Shell\LifeCheckerShell;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\I18n\Time;

/**
 * App\Model\Entity\Link Test Case
 * @group Unit
 * @group Entity
 * @group Model
 */
class LifeCheckerTest extends TestCase
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
     *
     * @var LifecheckerShell
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
        $this->shell = new LifeCheckerShell();
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

    public function testDeleteDead()
    {
        $initialNbDead = $this->Links->find(
            "rangeLife",
            ["min_life" => 100,
            "max_life" => 100]
        )->count();

        $this->assertNotEquals(0, $initialNbDead, "There are initial dead link");
        $this->shell->deleteDead();

        // After calling method, all dead link muste be erased
        $afterCmd = $this->Links->find(
            "rangeLife",
            ["min_life" => 100,
            "max_life" => 100]
        )->count();
        $this->assertEquals(0, $afterCmd, "delete_dead command have deleted link");

        // After calling method, all dead link muste be erased
        $afterCmd = $this->Links->find(
            "rangeLife",
            ["min_life" => 10,
            "max_life" => 100]
        )->count();
        $this->assertNotEquals(0, $afterCmd, "delete_dead did not delete all links");
    }
}
