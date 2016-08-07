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
        'private_token' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
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
            'private_token' => ['type' => 'unique', 'columns' => ['token'], 'length' => []],
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
            'private_token' => 'MTQwYjRkMTlmNWY4ZWQ5OTVmMmVhYmU0Y2I0YmM0YmJiZDE1Y2NkZg==',
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
            'private_token' => 'hYmU0Y2I0YmM0YmMTQwYjRkMTlmNWY4ZWQ5OTVmMmVJiZDE1Y2NkZg==',
            'max_views' => 42,
            'views' => 21,
            'death_time' => null,
            'user_id' => null,
            'status' => 1
        ],
        [
            'id' => 3,
            'title' => 'Dead link by views',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '2015-04-25 16:19:23',
            'modified' => '2015-04-25 16:19:23',
            'token' => '63f4ac58a6a1d0c6e83f027327d84610',
            'private_token' => 'hYmU0Y2I0YmM0YY4ZWQ5OTVmMmmMTQwYjRkMTlmNWVJiZDE1Y2NkZg==',
            'max_views' => 42,
            'views' => 43,
            'death_time' => null,
            'user_id' => null,
            'status' => 1
        ],
        [
            'id' => 4,
            'title' => 'No max_views',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => '6c6e83f027327d846103f4ac58a6a1d0',
            'private_token' => 'hYQ5OTVmMmmMTQwYjRkMTlmNWVJiZmU0Y2I0YmM0YY4ZWDE1Y2NkZg==',
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
            'private_token' => 'hYQ5OT2I0YmM0YY4ZWDE1Y2NkVmMmmMTQwYjRkMTlmNWVJiZmU0YZg==',
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
            'private_token' => 'YmM0YY4ZhYQ5OT2I0WDE1Y2NkVmMmmMTQwYjRkMTlmNWVJiZmU0YZg==',
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
            'private_token' => 'YmMmmMTQwYjRkMTlmNM0YY4ZhYQ5OT2I0WDE1Y2NkVmWVJiZmU0YZg==',
            'max_views' => null,
            'views' => 2,
            'death_time' => null,
            'status' => 1
        ],
        [
            'id' => 8,
            'title' => 'Disabled link',
            'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'f27d846104f4cc6c6a835ea6a1d00273',
            'private_token' => 'YmT2I0WDE1Y2NkVmWVJiZmUMmmMTQwYjRkMTlmNM0YY4ZhYQ5O0YZg==',
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
            'private_token' => 'mMTQwYjRkMTlmNM0YY4ZhYQ5O0YmT2I0WDE1Y2NkVmWVJiZmUMmYZg==',
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
            'private_token' => 'mMTQZhYQ5O0YmT2I0WwYjRkMTlmNM0YY4DE1Y2NkVmWVJiZmUMmYZg==',
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
            'private_token' => 'mMTQZhYQ5O0YmYY4DE1Y2NkVmWVJiZT2I0WwYjRkMTlmNM0mUMmYZg==',
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
            'private_token' => 'mMTQZhYQ5O0YmkVmWVJiZT2YY4DE1Y2NI0WwYjRkMTlmNM0mUMmYZg==',
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
            'private_token' => 'mMTQZhYQ5O0YmkVmWE1Y2NI0WwYjRkMTlmNM0mVJiZT2YY4DUMmYZg==',
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
            'private_token' => 'WwYjRkMTlmNM0mVJiZT2YY4DUmMTQZhYQ5O0YmkVmWE1Y2NI0mYZg==',
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
            'private_token' => 'WwYjRkMTlmNM0mDUmMTQVJiZT2YY4ZhYQ5O0YmkVmWE1Y2NI0mYZg==',
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
            'private_token' => 'WwYjRkMTY4ZhYQ5O0YmkVmWE1Y2NI0mYZglmNM0mDUmMTQVJiZT2Y==',
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
            'private_token' => '0mDUmMTQVJiWwYjRkMTY4ZhYQ5O0YmkVmWE1Y2NI0mYZglmNMZT2Y==',
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
            'private_token' => 'kMTY4ZhYQ5O0Ymk0mDUmMTQVJiWwYjRVmWE1Y2NI0mYZglmNMZT2Y==',
            'max_views' => 10,
            'views' => 9,
            'death_time' =>null,
            'status' => 1,
            'user_id' => 3,
            'google_captcha' => 1
        ],
        [
            'id' => 19,
            'title' => '80% life',
            'content' => 'content of id 19',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'a86gd0023fc86a2710164ccc63',
            'private_token' => 'kMTY4ZhJiWwYjRVmWE1Y2NI0mYZYQ5O0Ymk0mDUmMTQVglmNMZT2Y==',
            'max_views' => 10,
            'views' => 8,
            'death_time' =>null,
            'status' => 1,
            'user_id' => 3,
            'google_captcha' => 1
        ],
         [
            'id' => 20,
            'title' => '90% life',
            'content' => 'content of id 20',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'a03fc86a164ccc6386gd00235ea74271',
            'private_token' => 'kMT0Ymk0mDUY4ZhYQ5OmMTQVJiWwYjRVmWE1Y2NI0mYZglmNMZT2Y==',
            'max_views' => 10,
            'views' => 9,
            'death_time' =>null,
            'status' => 1,
            'user_id' => 4,
            'google_captcha' => 0
        ],
        [
            'id' => 21,
            'title' => 'Have a time limit component',
            'content' => 'content of id 20',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'ac8603fa164ccc6386gd00235ea74271',
            'private_token' => 'kMT0Ymk0mDUY4ZhYYjRVmWE1Y2NIQ5OmMTQVJiWw0mYZglmNMZT2Y==',
            'max_views' => null,
            'views' => 9,
            'death_time' => '1955-11-07 6:38:00',
            'status' => 1,
            'user_id' => 4,
            'google_captcha' => 0
        ],
        [
            'id' => 22,
            'title' => 'Have a time limit component (week)',
            'content' => 'content of id 20',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'ac8603fgd002a164ccc638635ea74271',
            'private_token' => 'kMT0Ymk0mDUYVmWE1Y2N4ZhYYjRIQ5OmMTQVJiWw0mYZglmNMZT2Y==',
            'max_views' => null,
            'views' => 9,
            'death_time' => '1955-11-13 6:38:00',
            'status' => 1,
            'user_id' => 4,
            'google_captcha' => 0
        ],
        [
            'id' => 23,
            'title' => 'Have a time limit component (month)',
            'content' => 'content of id 20',
            'created' => '1955-11-06 6:38:00',
            'modified' => '1955-11-07 6:38:00',
            'token' => 'ac8603fea74gd002a164ccc638635271',
            'private_token' => 'kMT0Ym4ZhYYjRIQ5OmMTk0mDUYVmWE1Y2NQVJiWw0mYZglmNMZT2Y==',
            'max_views' => null,
            'views' => 9,
            'death_time' => '1955-12-06 6:38:00',
            'status' => 1,
            'user_id' => 4,
            'google_captcha' => 0
        ]
    ];
}
