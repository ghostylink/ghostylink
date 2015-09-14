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
        'max_views' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'views' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'death_time' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'status' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 1, 'comment' => '', 'precision' => null],
        'google_captcha' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => 0, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'token' => ['type' => 'unique', 'columns' => ['token'], 'length' => []],
            'links_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
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
            'title' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2015-04-25 16:19:23',
            'modified' => '2015-04-25 16:19:23',
            'token' => 'a1d0c6e83f027327d8461063f4ac58a6',
            'max_views' => 1,
            'views' => 0,
            'death_time' => null,
            'user_id' => 1,
            'status' => 1
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
            'death_time' => null,
            'user_id' => 0,
            'status' => 1
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
            'death_time' => null,
            'status' => 1
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
            'death_time' => '1955-11-10 6:38:00',
            'status' => 1
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
            'death_time' => null,
            'status' => 1
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
            'death_time' => '1955-11-10 6:38:00',
            'status' => 1
        ],
        [
            'id' => 7,
            'title' => 'Unlimited link',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'f27d846103f4ac6c6e8358a6a1d00273',
            'max_views' => null,
            'views' => 2,
            'death_time' => '2155-11-10 6:38:00',
            'status' => 1
        ],
        [
            'id' => 8,
            'title' => 'Disabled link',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'f27d846104f4cc6c6a835ea6a1d00273',
            'max_views' => null,
            'views' => null,
            'death_time' => null,
            'status' => 0
        ],
        [
            'id' => 9,
            'title' => 'Enabled link',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'g27c846103f4cc6c6a835ea6a1d00273',
            'max_views' => null,
            'views' => null,
            'death_time' => null,
            'status' => 1
        ],
        [
            'id' => 10,
            'title' => 'User 1 id 10',
            'content' => 'content of id 10',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '46103f4cc6g27c8c6a835ea6a1d00273',
            'max_views' => null,
            'views' => null,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1
        ],
        [
            'id' => 11,
            'title' => 'User 1 id 11',
            'content' => 'content of id 11',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '46103f4cc6ag27c8c6835ea6a1d00273',
            'max_views' => null,
            'views' => null,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1
        ],
        [
            'id' => 12,
            'title' => 'User 1 id 12',
            'content' => 'content of id 12',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '46103f4cc66a835g27c8cea6a1d00273',
            'max_views' => 20,
            'views' => 6,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1
        ],
        [
            'id' => 13,
            'title' => 'User 1 id 13',
            'content' => 'content of id 13',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '464cc6g27103fc8c6a835ea6a1d00273',
            'max_views' => 10,
            'views' => 5,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1
        ],
        [
            'id' => 14,
            'title' => 'User 1 id 14',
            'content' => 'content of id 14',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '464cc6g8c6a835ea6a127103fcd00273',
            'max_views' => 10,
            'views' => 5,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1
        ],
        [
            'id' => 15,
            'title' => 'User 1 id 15',
            'content' => 'content of id 15',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '464ccc6a835ea6g27103fc86a1d00273',
            'max_views' => 10,
            'views' => 5,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1
        ],
          [
            'id' => 16,
            'title' => 'Google captcha',
            'content' => 'content of id 16',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '427103fc86a164ccc6a835ea6gd00273',
            'max_views' => null,
            'views' => 5,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1,
            'google_captcha' => 1
        ],
         [
            'id' => 17,
            'title' => '90% life',
            'content' => 'content of id 17',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'a835ea6gd0027427103fc86a164ccc63',
            'max_views' => 1000,
            'views' => 900,
            'death_time' => null,
            'status' => 1,
            'user_id' => 1,
            'google_captcha' => 1
        ],
         [
            'id' => 18,
            'title' => '90% life',
            'content' => 'content of id 18',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'a86gd00235ea7427103fc86a164ccc63',
            'max_views' => 10,
            'views' => 9,
            'death_time' =>null,
            'status' => 1,
            'user_id' => 3,
            'google_captcha' => 1
        ],
    ];
}
