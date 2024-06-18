<?php

declare(strict_types=1);

return [
    'frontend' => [
        'constants-middleware' => [
            'target' => \Vendor\Package\Middleware\ConstantsMiddleware::class,
            'before' => [
                'typo3/cms-frontend/authentication'
            ]
        ]
    ]
];
