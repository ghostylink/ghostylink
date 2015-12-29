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
    public $import = ['table' => 'alert_parameters', 'connection' => 'test_schema'];
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
