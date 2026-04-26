<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Sección: Datos de Usuario -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">
                <i class="fas fa-user me-2"></i>Datos de Acceso
            </h3>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Nombre completo')" />
                <x-text-input
                    id="name"
                    class="block mt-1 w-full"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Ej: Juan García López" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Correo electrónico')" />
                <x-text-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                    placeholder="juan@siemprecolgados.es" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Sección: Datos de Empleado -->
        <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
            <h3 class="text-lg font-semibold text-green-800 mb-3">
                <i class="fas fa-id-card me-2"></i>Datos Profesionales
            </h3>

            <!-- DNI -->
            <div>
                <x-input-label for="dni" :value="__('DNI/NIE')" />
                <x-text-input
                    id="dni"
                    class="block mt-1 w-full uppercase"
                    type="text"
                    name="dni"
                    :value="old('dni')"
                    required
                    autocomplete="off"
                    placeholder="12345678A"
                    maxlength="9"
                    oninput="this.value = this.value.toUpperCase()" />
                <x-input-error :messages="$errors->get('dni')" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Formato: 8 dígitos + letra (sin guion)</p>
            </div>

            <!-- Teléfono -->
            <div class="mt-4">
                <x-input-label for="telefono" :value="__('Teléfono de contacto')" />
                <x-text-input
                    id="telefono"
                    class="block mt-1 w-full"
                    type="tel"
                    name="telefono"
                    :value="old('telefono')"
                    autocomplete="tel"
                    placeholder="+34 612 345 678" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>

            <!-- Dirección -->
            <div class="mt-4">
                <x-input-label for="direccion" :value="__('Dirección postal')" />
                <x-text-input
                    id="direccion"
                    class="block mt-1 w-full"
                    type="text"
                    name="direccion"
                    :value="old('direccion')"
                    autocomplete="street-address"
                    placeholder="Calle Ejemplo, 123, 4ºB" />
                <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
            </div>

            <!-- Fecha de Alta -->
            <div class="mt-4">
                <x-input-label for="fecha_alta" :value="__('Fecha de incorporación')" />
                <x-text-input
                    id="fecha_alta"
                    class="block mt-1 w-full"
                    type="date"
                    name="fecha_alta"
                    :value="old('fecha_alta', date('Y-m-d'))"
                    required
                    max="{{ date('Y-m-d') }}" />
                <x-input-error :messages="$errors->get('fecha_alta')" class="mt-2" />
            </div>

            <!-- Tipo de Empleado (solo visible si hay admins registrando) -->
            @if(request()->has('allow_admin_register'))
            <div class="mt-4">
                <x-input-label for="tipo" :value="__('Tipo de empleado')" />
                <select
                    id="tipo"
                    name="tipo"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="operario" {{ old('tipo') === 'operario' ? 'selected' : '' }}>Operario</option>
                    <option value="administrador" {{ old('tipo') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                </select>
                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
            </div>
            @endif
        </div>

        <!-- Términos y condiciones (opcional pero recomendado) -->
        <div class="mt-4">
            <label for="terms" class="inline-flex items-center">
                <input
                    id="terms"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="terms"
                    required>
                <span class="ms-2 text-sm text-gray-600">
                    {!! __('Acepto los <a href=":url" target="_blank" class="underline text-sm text-gray-600 hover:text-gray-900">términos y condiciones</a>.' , ['url' => '#']) !!}
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <!-- Botones de acción -->
        <div class="flex items-center justify-end mt-6 gap-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

@push('scripts')
<script>
    // Validación cliente-side para DNI español (básica)
    document.getElementById('dni')?.addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
        if (value.length > 9) value = value.slice(0, 9);
        e.target.value = value;
    });

    // Validación para fecha no futura
    document.getElementById('fecha_alta')?.setAttribute('max', new Date().toISOString().split('T')[0]);
</script>
@endpush