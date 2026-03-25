<x-guest-layout>
    {{-- Fondo con colores de marca: Cobalto Profundo a Niebla Suave --}}
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-smart-cobalto to-smart-niebla py-12 px-4 transition-all">
        
        {{-- Tarjeta Blanca con diseño institucional --}}
        <div class="w-full max-w-sm bg-white shadow-2xl rounded-2xl overflow-hidden">
            
            {{-- Encabezado con Gris Carbón Profundo (smart-text) --}}
            <div class="pt-12 pb-5 px-10 text-center border-b border-gray-100">
                <h1 class="text-2xl font-extrabold text-smart-text tracking-tighter italic uppercase">Recuperar</h1>
            </div>

            <div class="p-10 pb-12 pt-8">
                <div class="mb-8 text-sm text-gray-500 leading-relaxed text-center">
                    {{ __('¿Olvidaste tu contraseña? Ingresa tu email y te enviaremos un enlace de recuperación.') }}
                </div>

                <x-auth-session-status class="mb-6 text-smart-success font-semibold text-center text-sm" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-10">
                        {{-- Input con Gris de Línea y foco en Azul de Interacción --}}
                        <input id="email" 
                            class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            placeholder="Correo Electrónico" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-smart-error" />
                    </div>

                    <div class="mb-8">
                        {{-- Botón con Azul de Interacción (smart-action) --}}
                        <button type="submit" class="w-full h-14 bg-smart-action text-white rounded-full font-bold text-base hover:bg-smart-cobalto transition-all shadow-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-smart-action focus:ring-offset-2">
                            {{ __('ENVIAR ENLACE') }}
                        </button>
                    </div>

                    <div class="text-center">
                        {{-- Enlace de retorno con tu color smart-link --}}
                        <a class="text-sm text-smart-link font-semibold hover:underline transition-colors" href="{{ route('login') }}">
                            {{ __('Volver al inicio de sesión') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>