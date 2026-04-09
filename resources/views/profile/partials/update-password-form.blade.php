<section>
    <header>
        <h2 class="text-lg font-black text-smart-cobalto-dark dark:text-white uppercase tracking-tight">
            {{ __('Actualizar Contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-smart-linea">
            {{ __('Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantenerse segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Contraseña Actual')" class="dark:text-smart-linea font-bold text-xs uppercase" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full dark:bg-black/20 dark:border-white/10 focus:ring-smart-cobalto" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-smart-error" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Nueva Contraseña')" class="dark:text-smart-linea font-bold text-xs uppercase" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full dark:bg-black/20 dark:border-white/10 focus:ring-smart-cobalto" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-smart-error" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Contraseña')" class="dark:text-smart-linea font-bold text-xs uppercase" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full dark:bg-black/20 dark:border-white/10 focus:ring-smart-cobalto" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-smart-error" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button class="bg-smart-cobalto hover:bg-smart-cobalto-dark font-black uppercase text-xs tracking-widest shadow-lg shadow-smart-cobalto/20">
                {{ __('Cambiar Contraseña') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-smart-success font-bold"
                >{{ __('Actualizada correctamente.') }}</p>
            @endif
        </div>
    </form>
</section>