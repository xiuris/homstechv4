<?php

use App\Jobs\EmitFiscalDocumentJob;
use App\Models\FiscalDocument;
use App\Models\User;
use App\Services\Contracts\WhatsAppService;
use App\Services\Fiscal\FiscalService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed();
});

it('mocks WhatsApp send when token is missing', function () {
    config(['services.whatsapp.token' => null, 'services.whatsapp.phone_id' => null]);

    $service = app(WhatsAppService::class);
    $result = $service->sendText('5511999999999', 'Teste de envio');

    expect($result->success)->toBeTrue()
        ->and($result->mocked)->toBeTrue()
        ->and($result->messageId)->not->toBeNull();
});

it('emits fiscal document through queue and stores files', function () {
    Storage::fake('private');

    $document = FiscalDocument::factory()->create(['status' => 'pending', 'protocol' => null]);

    (new EmitFiscalDocumentJob($document))->handle(app(FiscalService::class));

    $document->refresh();

    expect($document->status)->toBe('authorized')
        ->and($document->protocol)->not->toBeNull()
        ->and($document->xml_path)->not->toBeNull()
        ->and($document->pdf_path)->not->toBeNull();

    Storage::disk('private')->assertExists($document->xml_path);
    Storage::disk('private')->assertExists($document->pdf_path);
    expect($document->logs)->not->toBeEmpty();
});

it('marks fiscal document as failed when driver is missing', function () {
    config(['fiscal.drivers' => []]);
    $document = FiscalDocument::factory()->create(['status' => 'pending']);

    (new EmitFiscalDocumentJob($document))->handle(app(FiscalService::class));

    expect($document->fresh()->status)->toBe('failed');
});

it('dispatches emission job on controller store', function () {
    $user = User::factory()->create()->givePermissionTo('manage integrations');

    Bus::fake();

    $this->actingAs($user)
        ->post(route('fiscal-documents.store'), [
            'document_type' => 'nfe',
            'uf' => 'MS',
            'total' => 150,
        ])
        ->assertRedirect();

    Bus::assertDispatched(EmitFiscalDocumentJob::class);
});
