<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'username' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'UNIQUE_USERNAME' => ['type' => 'unique', 'columns' => ['username'], 'length' => []],
            'UNIQUE_EMAIL' => ['type' => 'unique', 'columns' => ['email'], 'length' => []],
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
            'username' => 'user1',
            // password : user1user1
            'password' => '$2y$10$ka4Cze0TozAg.wFj9wGz6eGC3/7.X228CQwYIwMATz.Mgh7wyyr8u',
            'email' => 'user1@ghostylink.org'
        ],
         [
            'id' => 2,
            'username' => 'userwithnolink',
            // password : user1user1
            'password' => '$2y$10$/7VU0iPe7EZhzpONsG94yejEFvlpjIjYCMq1mc7uAeYUGV8TgNW1y',
            'email' => 'nolinkuser@ghostylink.org'
        ],
          [
            'id' => 3,
            'username' => 'user3',
            // password : user1user1
            'password' => '$2y$10$/7VU0iPe7EZhzpONsG94yejEFvlpjIjYCMq1mc7uAeYUGV8TgNW1y',
            'email' => 'user3@ghostylink.org'
        ]
    ];
}
