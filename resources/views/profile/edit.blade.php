<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-smart-text tracking-wide uppercase">
            {{ __('Configuración de Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Sección: Información Personal --}}
            <div class="p-8 bg-white dark:bg-[#212121] shadow-xl sm:rounded-[2rem] border border-gray-100 dark:border-white/5 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Sección: Seguridad (Contraseña) --}}
            <div class="p-8 bg-white dark:bg-[#212121] shadow-xl sm:rounded-[2rem] border border-gray-100 dark:border-white/5 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Sección: Zona de Peligro (Eliminar Cuenta) --}}
            <div class="p-8 bg-white dark:bg-[#212121] shadow-xl sm:rounded-[2rem] border border-gray-100 dark:border-white/5 transition-all">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>