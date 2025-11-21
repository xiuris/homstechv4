<?php

namespace App\Http\Controllers;

use App\Jobs\EmitFiscalDocumentJob;
use App\Models\FiscalDocument;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class FiscalDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth.basic', 'permission:manage integrations']);
    }

    public function index(Request $request): View
    {
        $documents = FiscalDocument::query()
            ->where('company_id', Auth::user()->company_id)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('uf'), fn ($query) => $query->where('uf', strtoupper($request->string('uf'))))
            ->orderByDesc('id')
            ->get();

        return view('fiscal.index', [
            'documents' => $documents,
            'filters' => $request->only(['status', 'uf']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', FiscalDocument::class);

        return view('fiscal.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', FiscalDocument::class);

        $validated = $request->validate([
            'document_type' => ['required', 'in:nfe,nfce'],
            'uf' => ['required', 'size:2'],
            'total' => ['required', 'numeric', 'min:0.01'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'sale_id' => ['nullable', 'exists:sales,id'],
            'order_service_id' => ['nullable', 'exists:order_services,id'],
        ]);

        $document = FiscalDocument::create([
            'company_id' => Auth::user()->company_id,
            'customer_id' => $validated['customer_id'] ?? null,
            'sale_id' => $validated['sale_id'] ?? null,
            'order_service_id' => $validated['order_service_id'] ?? null,
            'document_type' => $validated['document_type'],
            'uf' => strtoupper($validated['uf']),
            'total' => $validated['total'],
            'status' => 'pending',
            'scheduled_at' => now(),
        ]);

        Bus::dispatch(new EmitFiscalDocumentJob($document));

        return redirect()->route('fiscal-documents.show', $document);
    }

    public function show(FiscalDocument $fiscalDocument): View
    {
        $this->authorize('view', $fiscalDocument);

        return view('fiscal.show', [
            'document' => $fiscalDocument->load('logs'),
        ]);
    }

    public function downloadXml(FiscalDocument $fiscalDocument)
    {
        $this->authorize('view', $fiscalDocument);

        abort_unless($fiscalDocument->xml_path && Storage::disk('private')->exists($fiscalDocument->xml_path), 404);

        return Storage::disk('private')->download($fiscalDocument->xml_path);
    }

    public function downloadPdf(FiscalDocument $fiscalDocument)
    {
        $this->authorize('view', $fiscalDocument);

        abort_unless($fiscalDocument->pdf_path && Storage::disk('private')->exists($fiscalDocument->pdf_path), 404);

        return Storage::disk('private')->download($fiscalDocument->pdf_path);
    }
}
