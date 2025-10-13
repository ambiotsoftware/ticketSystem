<div class="card mt-4">
    <h2 style="margin-top:0;">Cambiar Contraseña</h2>
    <p style="color: #666; margin-bottom: 1rem;">Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.</p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')
        
        <div class="row">
            <div class="col-6">
                <label for="update_password_current_password">Contraseña Actual</label>
                <input id="update_password_current_password" name="current_password" type="password" class="input" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-6">
                <label for="update_password_password">Nueva Contraseña</label>
                <input id="update_password_password" name="password" type="password" class="input" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-6">
                <label for="update_password_password_confirmation">Confirmar Contraseña</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input" autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-top: 1rem;">
            <button type="submit" class="btn">Guardar</button>
            
            @if (session('status') === 'password-updated')
                <span style="color: #28a745; font-size: 0.9rem; margin-left: 1rem;">¡Guardado!</span>
            @endif
        </div>
    </form>
</div>
