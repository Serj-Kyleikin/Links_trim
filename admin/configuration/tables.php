<?php

// Механизм MYISAM - табличная блокировка.

return [

    'core' => [
        "CREATE TABLE IF NOT EXISTS links (
            short_link CHAR(5),
            original_link VARCHAR(2000)
        ) ENGINE = MYISAM;"
    ]
];