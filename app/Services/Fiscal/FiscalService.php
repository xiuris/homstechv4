<?php

namespace App\Services\Fiscal;

use App\Models\FiscalDocument;
use App\Services\Fiscal\DTOs\FiscalEmissionResult;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class FiscalService
{
    public function emit(FiscalDocument $document): FiscalEmissionResult
    {
        $driverClass = $this->resolveDriver($document->uf);
        $driver = app($driverClass);

        $result = $driver->emit($document);

        Log::info('Fiscal emission processed', [
            'document_id' => $document->id,
            'status' => $result->status,
            'protocol' => $result->protocol,
        ]);

        return $result;
    }

    protected function resolveDriver(string $uf): string
    {
        $map = config('fiscal.drivers');
        $uf = strtoupper($uf);

        if (isset($map[$uf])) {
            return $map[$uf];
        }

        if (isset($map['default'])) {
            return $map['default'];
        }

        throw new InvalidArgumentException('No fiscal driver configured for UF '.$uf);
    }
}
