<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AlertParametersFixture
 *
 */
class AlertParametersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'life_threshold' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '67', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'type' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => 'email', 'comment' => '', 'precision' => null, 'fixed' => null],
        'sending_status' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'link_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'link_id' => ['type' => 'index', 'columns' => ['link_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'alert_parameters_ibfk_1' => ['type' => 'foreign', 'columns' => ['link_id'], 'references' => ['links', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'life_threshold' => 1,
            'type' => 'email',
            'sending_status' => 0,
            'link_id' => 1
        ],
        [
            'id' => 2,
            'life_threshold' => 1,
            'type' => 'email',
            'sending_status' => 0,
            'link_id' => 17
        ],
        [
            'id' => 3,
            'life_threshold' => 1,
            'type' => 'email',
            'sending_status' => 1, // Should not be retrieved in finders
            'link_id' => 18
        ],
        [
            'id' => 4,
            'life_threshold' => 1,
            'type' => 'email',
            'sending_status' => 0,
            'link_id' => 19 // Should not be retrieved in needMailAlert, link owner does not have email address defined
        ]
    ];
}
