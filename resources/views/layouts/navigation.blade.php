<nav x-data="{ open: false }" class="bg-white dark:bg-[#212121] border-b border-gray-100 dark:border-white/5 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-smart-cobalto" />
                    </a>
                </div>
            </div>

            {{-- Menú de Usuario (Desktop) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-black rounded-md text-gray-700 dark:text-white hover:text-smart-cobalto dark:hover:text-[#CCE8FF] focus:outline-none transition ease-in-out duration-150 bg-transparent gap-2">
                            <div class="text-base tracking-tight uppercase">{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-bold text-xs uppercase tracking-widest">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    class="font-bold text-xs uppercase tracking-widest text-smart-error"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburguesa (Móvil) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-smart-linea hover:text-smart-cobalto dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menú Responsivo (Móvil) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t dark:border-white/5">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold uppercase text-xs tracking-widest">
                {{ __('Inicio') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.usuarios')" :active="request()->routeIs('admin.usuarios')" class="font-bold uppercase text-xs tracking-widest">
                {{ __('Usuarios') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.incidencias')" :active="request()->routeIs('admin.incidencias')" class="font-bold uppercase text-xs tracking-widest">
                {{ __('Incidencias') }}
            </x-responsive-nav-link>

            {{-- ¡AQUÍ ESTÁ EL ENLACE DE ESTADÍSTICAS PARA MÓVIL! --}}
            <x-responsive-nav-link :href="route('admin.estadisticas')" :active="request()->routeIs('admin.estadisticas')" class="font-bold uppercase text-xs tracking-widest">
                {{ __('Estadísticas') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-white/5">
            <div class="px-4 mb-3">
                <div class="font-black text-base text-smart-cobalto-dark dark:text-white uppercase tracking-tight">{{ Auth::user()->name }}</div>
                <div class="font-medium text-xs text-gray-500 dark:text-smart-linea">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold uppercase text-xs tracking-widest">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="font-bold uppercase text-xs tracking-widest text-smart-error"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>