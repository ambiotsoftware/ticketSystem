<div class="card mt-4" style="border-color: #dc3545;">
    <h2 style="margin-top:0; color: #dc3545;">Eliminar Cuenta</h2>
    <p style="color: #666; margin-bottom: 1rem;">Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, descarga cualquier dato o información que desees conservar.</p>

    <button type="button" class="btn danger" onclick="if(confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')) { document.getElementById('delete-form').style.display = 'block'; }">Eliminar Cuenta</button>
    
    <div id="delete-form" style="display: none; margin-top: 1rem; padding: 1rem; border: 1px solid #dc3545; border-radius: 4px; background-color: #f8f9fa;">
        <h3 style="color: #dc3545;">Confirmar eliminación de cuenta</h3>
        <p style="color: #666; margin-bottom: 1rem;">Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.</p>
        
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            
            <div style="margin-bottom: 1rem;">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" class="input" placeholder="Contraseña" required style="max-width: 300px;">
                @error('password', 'userDeletion')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <button type="button" class="btn secondary" onclick="document.getElementById('delete-form').style.display = 'none';">Cancelar</button>
                <button type="submit" class="btn danger">Eliminar Cuenta</button>
            </div>
        </form>
    </div>
</div>
