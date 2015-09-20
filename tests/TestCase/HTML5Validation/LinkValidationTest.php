<?php

use Cake\TestSuite\IntegrationTestCase;
use Kevintweber\PhpunitMarkupValidators\Assert\AssertHTML5;
use Cake\I18n\Time;

/**
 * @group HTML5Validation
 * @group Functional
 */
class LinkValidationTest extends IntegrationTestCase
{
    public $fixtures = [
        'Links' => 'app.links',
        'Users' => 'app.users'
    ];

    public function testView()
    {
         $this->get('/a1d0c6e83f027327d8461063f4ac58a6');
         AssertHTML5::isValidMarkup($this->_response->body(), 'Link view is ok for link with max_views');

        $now = new Time('1955-11-09 6:38:00');
        Time::setTestNow($now);
         $this->get('/6c6e83f027327d846103f4ac58a6a1d0');
         AssertHTML5::isValidMarkup($this->_response->body(), 'Link view is ok for link with death_time');
    }

    public function testAdd()
    {
         $this->get('/');
         AssertHTML5::isValidMarkup($this->_response->body(), 'Root site is valid');
    }
}