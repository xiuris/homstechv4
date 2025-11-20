<?php

namespace App\Jobs;

use App\Models\FiscalDocument;
use App\Models\FiscalDocumentLog;
use App\Services\Fiscal\FiscalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmitFiscalDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public FiscalDocument $document) {}

    public function handle(FiscalService $fiscalService): void
    {
        $this->document->refresh();

        try {
            $result = $fiscalService->emit($this->document);

            $this->document->update([
                'status' => $result->status,
                'protocol' => $result->protocol,
                'message' => $result->message,
                'xml_path' => $result->xmlPath,
                'pdf_path' => $result->pdfPath,
                'last_emitted_at' => now(),
                'attempts' => $this->document->attempts + 1,
            ]);

            FiscalDocumentLog::create([
                'fiscal_document_id' => $this->document->id,
                'status' => $result->status,
                'message' => $result->message,
                'meta' => ['protocol' => $result->protocol],
            ]);
        } catch (Throwable $e) {
            Log::error('Failed to emit fiscal document', [
                'document_id' => $this->document->id,
                'error' => $e->getMessage(),
            ]);

            $this->document->update([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'attempts' => $this->document->attempts + 1,
            ]);

            FiscalDocumentLog::create([
                'fiscal_document_id' => $this->document->id,
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
