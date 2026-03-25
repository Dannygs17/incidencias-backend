<x-guest-layout>
    {{-- Degradado de fondo con tus colores de marca: Cobalto Profundo a Niebla Suave --}}
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-smart-cobalto to-smart-niebla py-12 px-4 transition-all">
        
        {{-- Tarjeta Blanca con sombra suave --}}
        <div class="w-full max-w-sm bg-white shadow-2xl rounded-2xl overflow-hidden">
            
            {{-- Encabezado con Gris Carbón Profundo (smart-text) --}}
            <div class="pt-12 pb-5 px-10 text-center border-b border-gray-100">
                <h1 class="text-3xl font-extrabold text-smart-text tracking-tighter italic">Iniciar Sesión</h1>
            </div>

            <x-auth-session-status class="mb-4 px-10 pt-5 text-center text-sm font-medium" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="p-10 pb-12 pt-8">
                @csrf

                <div class="mb-7">
                    {{-- Línea inferior con Gris de Línea (smart-line) --}}
                    {{-- Al hacer clic cambia al Azul de Interacción (smart-action) --}}
                    <input id="email" 
                        class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="Correo Electrónico" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-smart-error" />
                </div>

                <div class="mb-5">
                    <input id="password" 
                        class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                        placeholder="Contraseña" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-smart-error" />
                </div>

                <div class="flex items-center justify-start mb-10">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-400 hover:text-smart-action focus:outline-none transition-colors" 
                           href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <div class="mb-10">
                    <button type="submit" class="w-full h-14 bg-smart-action text-white rounded-full font-bold text-base hover:bg-smart-cobalto transition-all shadow-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-smart-action focus:ring-offset-2">
                        {{ __('ENTRAR') }}
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿Aún no eres un usuario? 
                        <a class="text-smart-action font-semibold hover:underline transition-colors ms-1" href="{{ route('register') }}">
                            {{ __('Regístrate Aquí') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>