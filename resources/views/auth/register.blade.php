<x-guest-layout>
    {{-- Fondo con los colores de marca: Cobalto Profundo a Niebla Suave --}}
    <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-smart-cobalto to-smart-niebla py-12 px-4 transition-all">
        
        {{-- Tarjeta Blanca con bordes redondeados y sombra --}}
        <div class="w-full max-w-sm bg-white shadow-2xl rounded-2xl overflow-hidden">
            
            {{-- Encabezado con Gris Carbón Profundo (smart-text) --}}
            <div class="pt-12 pb-5 px-10 text-center border-b border-gray-100">
                <h1 class="text-3xl font-extrabold text-smart-text tracking-tighter italic">Registro</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="p-10 pb-12 pt-8">
                @csrf

                <div class="mb-6">
                    <input id="name" 
                        class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="Nombre completo" /> 
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-smart-error" />
                </div>

                <div class="mb-6">
                    <input id="email" 
                        class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username"
                        placeholder="Correo electrónico" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-smart-error" />
                </div>

                <div class="mb-6">
                    <input id="password" 
                        class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none"
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password"
                        placeholder="Contraseña" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-smart-error" />
                </div>

                <div class="mb-10">
                    <input id="password_confirmation" 
                        class="block w-full border-0 border-b-2 border-smart-line bg-white px-0 pb-2 placeholder-gray-400 text-smart-text text-base transition-colors focus:border-smart-action focus:ring-0 focus:outline-none"
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Confirmar contraseña" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-smart-error" />
                </div>

                <div class="mb-8">
                    <button type="submit" class="w-full h-14 bg-smart-action text-white rounded-full font-bold text-base hover:bg-smart-cobalto transition-all shadow-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-smart-action focus:ring-offset-2">
                        {{ __('REGISTRARSE') }}
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        ¿Ya te registraste?
                        <a class="text-smart-action font-semibold hover:underline transition-colors ms-1" href="{{ route('login') }}">
                            {{ __('Iniciar sesión') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>