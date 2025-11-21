<?php

namespace App\Services\Fiscal\Drivers\BR\MS;

use App\Models\FiscalDocument;
use App\Services\Fiscal\DTOs\FiscalEmissionResult;
use App\Services\Fiscal\FiscalDriver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MockFiscalDriver implements FiscalDriver
{
    public function emit(FiscalDocument $document): FiscalEmissionResult
    {
        $protocol = 'PROTO-'.Str::upper(Str::random(10));
        $xmlContent = '<xml><document id="'.$document->id.'" status="authorized" /></xml>';
        $pdfContent = 'PDF placeholder for document '.$document->id;

        $xmlPath = 'fiscal/xml/'.$protocol.'.xml';
        $pdfPath = 'fiscal/pdf/'.$protocol.'.pdf';

        Storage::disk('private')->put($xmlPath, $xmlContent);
        Storage::disk('private')->put($pdfPath, $pdfContent);

        Log::info('Mock fiscal emission generated', [
            'document_id' => $document->id,
            'protocol' => $protocol,
        ]);

        return new FiscalEmissionResult(
            status: 'authorized',
            protocol: $protocol,
            message: 'Documento autorizado em ambiente simulado',
            xmlPath: $xmlPath,
            pdfPath: $pdfPath,
        );
    }
}
