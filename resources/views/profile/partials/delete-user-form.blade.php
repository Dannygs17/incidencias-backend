<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-smart-error dark:text-white uppercase tracking-tight">
            {{ __('Eliminar Cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-smart-linea">
            {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se borrarán de forma permanente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-smart-error hover:bg-red-700 font-black uppercase text-xs tracking-widest shadow-lg shadow-smart-error/20"
    >{{ __('Eliminar Cuenta') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        {{-- Fondo Gris Carbón para el modal en modo oscuro --}}
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white dark:bg-[#212121]">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">
                {{ __('¿Estás seguro de que quieres eliminar tu cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-smart-linea leading-relaxed">
                {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se borrarán de forma permanente. Por favor, introduzca su contraseña para confirmar que desea eliminar su cuenta permanentemente.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 dark:bg-black/20 dark:border-white/10 focus:ring-smart-error"
                    placeholder="{{ __('Introduce tu contraseña') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-smart-error" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="dark:bg-white/5 dark:text-white border-none uppercase font-black text-[10px] tracking-widest">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="bg-smart-error hover:bg-red-700 font-black uppercase text-[10px] tracking-widest">
                    {{ __('Confirmar Eliminación') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>