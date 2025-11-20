<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(Request $request): View
    {
        $services = Service::where('company_id', $request->user()->company_id)
            ->orderBy('name')
            ->paginate(10);

        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        return view('services.create', [
            'service' => new Service,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['company_id'] = $request->user()->company_id;
        $data['is_active'] = $request->boolean('is_active', true);

        $service = Service::create($data);

        return redirect()->route('services.show', $service)
            ->with('status', 'Serviço criado com sucesso.');
    }

    public function show(Service $service): View
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', $service->is_active);

        $service->update($data);

        return redirect()->route('services.show', $service)
            ->with('status', 'Serviço atualizado com sucesso.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('status', 'Serviço removido com sucesso.');
    }
}
