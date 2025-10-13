<div class="card mt-4">
    <h2 style="margin-top:0;">Información del Perfil</h2>
    <p style="color: #666; margin-bottom: 1rem;">Actualiza la información de tu cuenta y dirección de email.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        
        <div class="row">
            <div class="col-6">
                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" class="input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-6">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="input" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
                
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="margin-top: 0.5rem;">
                        <p style="color: #666; font-size: 0.9rem;">
                            Tu dirección de email no está verificada.
                            <button form="send-verification" type="submit" style="background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer;">Haz clic aquí para reenviar el email de verificación.</button>
                        </p>
                        
                        @if (session('status') === 'verification-link-sent')
                            <p style="color: #28a745; font-size: 0.9rem; margin-top: 0.5rem;">
                                Se ha enviado un nuevo enlace de verificación a tu email.
                            </p>
                        @endif
                    </div>
                @endif
            </div>
            
            @if(auth()->user()->role === 'client')
                <div class="col-6">
                    <label for="company_name">Nombre de la Empresa</label>
                    <input id="company_name" name="company_name" type="text" class="input" value="{{ old('company_name', $user->company_name) }}" autocomplete="organization">
                    @error('company_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        </div>
        
        <div style="margin-top: 1rem;">
            <button type="submit" class="btn">Guardar</button>
            
            @if (session('status') === 'profile-updated')
                <span style="color: #28a745; font-size: 0.9rem; margin-left: 1rem;">¡Guardado!</span>
            @endif
        </div>
    </form>
</div>
