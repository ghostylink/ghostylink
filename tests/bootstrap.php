<?php
use Cake\Log\Log;

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */
$LOAD_TEST_CONFIG = true;
require_once 'Tests/Selenium2TestCase/BaseTestCase.php';

PHPUnit_Extensions_Selenium2TestCase::shareSession(true);
require dirname(__DIR__) . '/config/bootstrap.php';
