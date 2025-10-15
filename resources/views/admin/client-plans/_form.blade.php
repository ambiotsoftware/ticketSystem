@csrf
<div class="card-body">
    <div class="row">
        {{-- Usuario --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label for="user_id" class="form-label required">{{ __('Usuario') }}</label>
                <select
                    name="user_id"
                    id="user_id"
                    class="form-select form-select-sm @error('user_id') is-invalid @enderror"
                >
                    <option value="">{{ __('Seleccione...') }}</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('user_id', $clientPlan->user_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Plan --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label for="plan_id" class="form-label required">{{ __('Plan') }}</label>
                <select
                    name="plan_id"
                    id="plan_id"
                    class="form-select form-select-sm @error('plan_id') is-invalid @enderror"
                >
                    <option value="">{{ __('Seleccione...') }}</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $clientPlan->plan_id) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                </select>
                @error('plan_id')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Estado --}}
        <div class="col-md-4">
            <div class="mb-3">
                <label for="active" class="form-label required">{{ __('Estado') }}</label>
                <select
                    name="active"
                    id="active"
                    class="form-select form-select-sm @error('active') is-invalid @enderror"
                >
                    <option value="1" {{ old('active', $clientPlan->active) == 1 ? 'selected' : '' }}>{{ __('Activo') }}</option>
                    <option value="0" {{ old('active', $clientPlan->active) == 0 ? 'selected' : '' }}>{{ __('Inactivo') }}</option>
                </select>
                @error('active')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Fecha inicio --}}
        <div class="col-md-3">
            <div class="mb-3">
                <label for="start_date" class="form-label required">{{ __('Fecha de inicio') }}</label>
                <input
                    type="date"
                    name="start_date"
                    id="start_date"
                    value="{{ old('start_date', $clientPlan->start_date?->format('Y-m-d')) }}"
                    class="form-control form-control-sm @error('start_date') is-invalid @enderror"
                >
                @error('start_date')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Fecha fin --}}
        <div class="col-md-3">
            <div class="mb-3">
                <label for="end_date" class="form-label required">{{ __('Fecha de fin') }}</label>
                <input
                    type="date"
                    name="end_date"
                    id="end_date"
                    value="{{ old('end_date', $clientPlan->end_date?->format('Y-m-d')) }}"
                    class="form-control form-control-sm @error('end_date') is-invalid @enderror"
                >
                @error('end_date')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Costo personalizado --}}
        <div class="col-md-3">
            <div class="mb-3">
                <label for="custom_plan_cost" class="form-label">{{ __('Costo personalizado') }}</label>
                <input
                    type="number"
                    step="0.01"
                    name="custom_plan_cost"
                    id="custom_plan_cost"
                    value="{{ old('custom_plan_cost', $clientPlan->custom_plan_cost) }}"
                    class="form-control form-control-sm @error('custom_plan_cost') is-invalid @enderror"
                    placeholder="Ej: 99.90"
                >
                @error('custom_plan_cost')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Tarifa extra personalizada --}}
        <div class="col-md-3">
            <div class="mb-3">
                <label for="custom_extra_hour_rate" class="form-label">{{ __('Tarifa hora extra') }}</label>
                <input
                    type="number"
                    step="0.01"
                    name="custom_extra_hour_rate"
                    id="custom_extra_hour_rate"
                    value="{{ old('custom_extra_hour_rate', $clientPlan->custom_extra_hour_rate) }}"
                    class="form-control form-control-sm @error('custom_extra_hour_rate') is-invalid @enderror"
                    placeholder="Ej: 10.00"
                >
                @error('custom_extra_hour_rate')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="card-footer">
    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.client-plans.index') }}" class="btn btn-sm btn-secondary me-1">
            <i class="bi bi-arrow-left-circle"></i> {{ __('Regresar') }}
        </a>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="bi bi-save"></i> {{ $btnText }}
        </button>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            // Validar que la fecha de fin sea mayor o igual
            $('#start_date, #end_date').on('change', function() {
                const start = $('#start_date').val();
                const end = $('#end_date').val();

                if (start && end && end < start) {
                    alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
                    $('#end_date').val('');
                }
            });
        });
    </script>
@endpush
