<?php
return [
    [
        'user_id' => 1,
        'token' => 'admin-token-correct',
        'expired_at' => time() + 3600,
    ],
    [
        'user_id' => 1,
        'token' => 'admin-token-expired',
        'expired_at' => time() - 3600,
    ],
    [
        'user_id' => 2,
        'token' => 'author-token-correct',
        'expired_at' => time() + 3600,
    ],
    [
        'user_id' => 2,
        'token' => 'author-token-expired',
        'expired_at' => time() - 3600,
    ],
];