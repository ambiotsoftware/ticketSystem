@if (session('success'))
    <div class="card" style="border-left: 4px solid #16a34a; margin-bottom: 16px;">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="card" style="border-left: 4px solid #dc2626; margin-bottom: 16px;">
        {{ session('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="card" style="border-left: 4px solid #dc2626; margin-bottom: 16px;">
        <strong>Corrige los siguientes errores:</strong>
        <ul class="mt-2" style="margin-left: 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

