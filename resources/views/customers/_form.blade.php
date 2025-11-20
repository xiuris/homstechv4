@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="document">Documento</label>
        <input type="text" class="form-control" id="document" name="document" value="{{ old('document', $customer->document) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="email">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="phone">Telefone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="mobile_phone">Celular</label>
        <input type="text" class="form-control" id="mobile_phone" name="mobile_phone" value="{{ old('mobile_phone', $customer->mobile_phone) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="state">UF</label>
        <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $customer->state) }}" maxlength="2">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="city">Cidade</label>
        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $customer->city) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="zip_code">CEP</label>
        <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $customer->zip_code) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="address">Endereço</label>
        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $customer->address) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="reseller_id">Revendedor</label>
        <select class="form-select" id="reseller_id" name="reseller_id">
            <option value="">-- Nenhum --</option>
            @foreach ($resellers as $id => $name)
                <option value="{{ $id }}" @selected(old('reseller_id', $customer->reseller_id) == $id)>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label" for="notes">Observações</label>
        <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
    </div>
</div>
<div class="mt-4 d-flex justify-content-end gap-2">
    <a class="btn btn-secondary" href="{{ url()->previous() }}">Cancelar</a>
    <button type="submit" class="btn btn-primary">Salvar</button>
</div>
