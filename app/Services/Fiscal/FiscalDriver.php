<?php

namespace App\Services\Fiscal;

use App\Models\FiscalDocument;
use App\Services\Fiscal\DTOs\FiscalEmissionResult;

interface FiscalDriver
{
    public function emit(FiscalDocument $document): FiscalEmissionResult;
}
