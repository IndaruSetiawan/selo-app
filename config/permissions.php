<?php
return [
    'admin' => ['*'],
    'staff' => [
        'dashboard.read',
        'orders.read',
        'orders.update',
        'orders.delete',
        'store.read',
        'analytics.read'
    ],
];