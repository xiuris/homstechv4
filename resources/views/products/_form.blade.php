@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="sku">SKU</label>
        <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="category">Categoria</label>
        <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $product->category) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="retail_price">Preço varejo</label>
        <input type="number" step="0.01" class="form-control" id="retail_price" name="retail_price" value="{{ old('retail_price', $product->retail_price) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="wholesale_price">Preço atacado</label>
        <input type="number" step="0.01" class="form-control" id="wholesale_price" name="wholesale_price" value="{{ old('wholesale_price', $product->wholesale_price) }}">
    </div>
    <div class="col-md-2">
        <label class="form-label" for="stock">Estoque</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
    </div>
    <div class="col-md-2">
        <label class="form-label" for="stock_minimum">Estoque mínimo</label>
        <input type="number" class="form-control" id="stock_minimum" name="stock_minimum" value="{{ old('stock_minimum', $product->stock_minimum) }}" min="0" required>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Descrição</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="col-12 form-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
        <label class="form-check-label" for="is_active">Ativo para vendas</label>
    </div>
</div>
<div class="mt-4 d-flex justify-content-end gap-2">
    <a class="btn btn-secondary" href="{{ url()->previous() }}">Cancelar</a>
    <button type="submit" class="btn btn-primary">Salvar</button>
</div>
