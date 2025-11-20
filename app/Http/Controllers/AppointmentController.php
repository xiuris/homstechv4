<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\OrderService;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Appointment::class);

        $companyId = $request->user()->company_id;
        $appointments = Appointment::with(['customer', 'orderService', 'technician'])
            ->where('company_id', $companyId)
            ->orderBy('starts_at')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Appointment::class);

        $companyId = $request->user()->company_id;

        return view('appointments.create', [
            'customers' => Customer::where('company_id', $companyId)->get(),
            'orders' => OrderService::where('company_id', $companyId)->get(),
            'technicians' => User::permission('manage order services')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Appointment::class);

        $data = $request->validate([
            'order_service_id' => ['nullable', 'exists:order_services,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'technician_id' => ['nullable', 'exists:users,id'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'status' => ['required', 'string'],
            'is_blocked' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['company_id'] = $request->user()->company_id;
        $data['is_blocked'] = $data['is_blocked'] ?? false;

        Appointment::create($data);

        return redirect()->route('appointments.index')->with('status', __('Agendamento criado'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorize('update', $appointment);

        $data = $request->validate([
            'status' => ['required', 'string'],
            'is_blocked' => ['nullable', 'boolean'],
        ]);

        $appointment->update([
            'status' => $data['status'],
            'is_blocked' => $data['is_blocked'] ?? false,
        ]);

        return redirect()->route('appointments.index')->with('status', __('Agendamento atualizado'));
    }
}
