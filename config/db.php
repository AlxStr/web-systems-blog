<?php

return [
    'class' => 'yii\db\Connection',

    'dsn' => getenv('DB_CONNECTION') .
        ':host=' . getenv('DB_HOST') .
        ';dbname=' . getenv('DB_NAME'),

    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => getenv('DB_CHARSET'),
    'tablePrefix' => getenv('DB_TABLE_PREFIX'),

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
