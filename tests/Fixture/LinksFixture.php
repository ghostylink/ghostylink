<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LinksFixture
 *
 */
class LinksFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'content' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'token' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'max_views' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'views' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'death_time' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'token' => ['type' => 'unique', 'columns' => ['token'], 'length' => []],
        ],
        '_options' => [
'engine' => 'InnoDB', 'collation' => 'utf8_general_ci'
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
            'title' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2015-04-25 16:19:23',
            'modified' => '2015-04-25 16:19:23',
            'token' => 'a1d0c6e83f027327d8461063f4ac58a6',
            'max_views' => 1,
            'views' => 0,
            'death_time' => null
        ],
        [
            'id' => 2,
            'title' => 'Half life',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2015-04-25 16:19:23',
            'modified' => '2015-04-25 16:19:23',
            'token' => 'f027327d8461063f4ac58a6a1d0c6e83',
            'max_views' => 42,
            'views' => 21,
            'death_time' => null
        ],
        [
            'id' => 3,
            'title' => 'Dead link by views',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2015-04-25 16:19:23',
            'modified' => '2015-04-25 16:19:23',
            'token' => '63f4ac58a6a1d0c6e83f027327d84610',
            'max_views' => 42,
            'views' => 43,
            'death_time' => null
        ],
        [
            'id' => 4,
            'title' => 'No max_views',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '6c6e83f027327d846103f4ac58a6a1d0',
            'max_views' => null,
            'views' => 43,
            'death_time' => '1955-11-10 6:38:00'
        ],
        [
            'id' => 5,
            'title' => 'No death_time',
            'content' => 'If my calculations are correct, when this baby hits 88 miles per hour... you\'re gonna see some serious shit',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1985-10-26 09:00:00',
            'token' => '631d0c6e83f027327d84610f4ac58a6a',
            'max_views' => 5,
            'views' => 1,
            'death_time' => null
        ],
        [
            'id' => 6,
            'title' => 'Both max_views and death_time',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '6c6e83f27d846103f4ac58a6a1d00273',
            'max_views' => 5,
            'views' => 2,
            'death_time' => '1955-11-10 6:38:00'
        ]
    ];
}
