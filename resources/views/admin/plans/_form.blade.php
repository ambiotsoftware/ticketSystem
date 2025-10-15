@csrf

<div class="card-body">
    {{-- Nombre del plan --}}
    <div class="mb-3">
        <label for="name" class="form-label required">{{ __('Nombre del Plan') }}</label>
        <input
            type="text"
            name="name"
            id="name"
            value="{{ old('name', $plan->name) }}"
            class="form-control form-control-sm @error('name') is-invalid @enderror"
        >
        @error('name')
        <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-4">
            {{-- Horas incluidas --}}
            <div class="mb-3">
                <label for="hours_included" class="form-label required">{{ __('Horas incluidas') }}</label>
                <input
                    type="number"
                    name="hours_included"
                    id="hours_included"
                    min="0"
                    value="{{ old('hours_included', $plan->hours_included) }}"
                    class="form-control form-control-sm @error('hours_included') is-invalid @enderror"
                >
                @error('hours_included')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            {{-- Costo del plan --}}
            <div class="mb-3">
                <label for="plan_cost" class="form-label required">{{ __('Costo del Plan') }}</label>
                <input
                    type="number"
                    name="plan_cost"
                    id="plan_cost"
                    step="0.01"
                    min="0"
                    value="{{ old('plan_cost', $plan->plan_cost) }}"
                    class="form-control form-control-sm @error('plan_cost') is-invalid @enderror"
                >
                @error('plan_cost')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            {{-- Tarifa por hora extra --}}
            <div class="mb-3">
                <label for="extra_hour_rate" class="form-label required">{{ __('Tarifa por hora extra') }}</label>
                <input
                    type="number"
                    name="extra_hour_rate"
                    id="extra_hour_rate"
                    step="0.01"
                    min="0"
                    value="{{ old('extra_hour_rate', $plan->extra_hour_rate) }}"
                    class="form-control form-control-sm @error('extra_hour_rate') is-invalid @enderror"
                >
                @error('extra_hour_rate')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            {{-- Ciclo de facturación --}}
            <div class="mb-3">
                <label for="billing_cycle" class="form-label required">{{ __('Ciclo de Facturación') }}</label>
                <select
                    name="billing_cycle"
                    id="billing_cycle"
                    class="form-select form-select-sm @error('billing_cycle') is-invalid @enderror"
                >
                    <option value="">{{ __('Seleccione...') }}</option>
                    @foreach($billingCycle as $id => $name)
                        <option value="{{ $id }}" {{ old('billing_cycle', $plan->billing_cycle?->value) == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('billing_cycle')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            {{-- Estado --}}
            <div class="mb-3">
                <label for="active" class="form-label required">{{ __('Estado') }}</label>
                <select
                    name="active"
                    id="active"
                    class="form-select form-select-sm @error('active') is-invalid @enderror"
                >
                    <option value="1" {{ old('active', $plan->active) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                    <option value="0" {{ old('active', $plan->active) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                </select>
                @error('active')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    {{-- Descripción --}}
    <div class="mb-3">
        <label for="description" class="form-label">{{ __('Descripción') }}</label>
        <textarea
            name="description"
            id="description"
            rows="3"
            class="form-control form-control-sm @error('description') is-invalid @enderror"
        >{{ old('description', $plan->description) }}</textarea>
        @error('description')
        <span class="error invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="card-footer">
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-sm btn-secondary me-1">{{ __('Regresar') }}</a>
        <button type="submit" class="btn btn-sm btn-primary">{{ $btnText }}</button>
    </div>
</div>

