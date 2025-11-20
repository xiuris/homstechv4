<?php

namespace App\Services\Fiscal\DTOs;

class FiscalEmissionResult
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $protocol = null,
        public readonly ?string $message = null,
        public readonly ?string $xmlPath = null,
        public readonly ?string $pdfPath = null,
    ) {}
}
