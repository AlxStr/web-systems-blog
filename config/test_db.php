<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=127.0.0.1;port=33062;dbname=homestead_test';
$db['username']='root';
$db['password']='secret';
$db['charset']='utf8';
return $db;
