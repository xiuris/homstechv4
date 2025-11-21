<?php

return [
    'drivers' => [
        'MS' => env('FISCAL_DRIVER') === 'mock'
            ? \App\Services\Fiscal\Drivers\BR\MS\MockFiscalDriver::class
            : env('FISCAL_DRIVER', \App\Services\Fiscal\Drivers\BR\MS\MockFiscalDriver::class),
        'default' => env('FISCAL_DRIVER') === 'mock'
            ? \App\Services\Fiscal\Drivers\BR\MS\MockFiscalDriver::class
            : env('FISCAL_DRIVER', \App\Services\Fiscal\Drivers\BR\MS\MockFiscalDriver::class),
    ],
];
