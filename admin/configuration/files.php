<?php

return [
    'core' => [
        '0740' => [
            'configurations/connection.php' => '',
            '.htaccess' => "",
            'logs/.htaccess' => "<FilesMatch '.txt$'>
    deny from all
</FilesMatch>",
            'configurations/.htaccess' => "<FilesMatch '.php$'>
    deny from all
</FilesMatch>"
        ]
    ],
    'plugins' => [
        '0740' => [
            'logs/diagnostic_errors.txt' => ''
        ]
    ]
];