<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-gray-900 text-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-14 sm:h-16">
                    <div class="flex items-center gap-4 sm:gap-8 flex-wrap">
                        <a href="{{ route('dashboard') }}" class="font-semibold text-sm sm:text-base tracking-tight">
                            Sistema de Tickets
                        </a>
                        @auth
                            @if (auth()->user()->rol === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-300 hover:text-white">Admin</a>
                                <a href="{{ route('admin.tickets.index') }}" class="text-sm text-gray-300 hover:text-white">Tickets</a>
                                <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-gray-300 hover:text-white">Usuarios</a>
                            @endif
                            @if (auth()->user()->rol === 'gerente')
                                <a href="{{ route('gerente.dashboard') }}" class="text-sm text-gray-300 hover:text-white">Dashboard</a>
                                <a href="{{ route('gerente.reportes') }}" class="text-sm text-gray-300 hover:text-white">Reportes</a>
                                <a href="{{ route('gerente.tickets.index') }}" class="text-sm text-gray-300 hover:text-white">Tickets</a>
                            @endif
                            @if (auth()->user()->rol === 'usuario')
                                <a href="{{ route('usuario.dashboard') }}" class="text-sm text-gray-300 hover:text-white">Mi panel</a>
                                <a href="{{ route('usuario.tickets.index') }}" class="text-sm text-gray-300 hover:text-white">Mis tickets</a>
                                <a href="{{ route('usuario.tickets.create') }}" class="text-sm text-gray-300 hover:text-white">Nuevo ticket</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="text-sm text-gray-300 hover:text-white">Perfil</a>
                        @endauth
                    </div>
                    @auth
                        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                            <span class="text-xs sm:text-sm text-gray-300 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <span class="text-xs uppercase px-2 py-0.5 rounded bg-gray-700 text-gray-200">{{ auth()->user()->rol }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs sm:text-sm px-2 py-1 rounded border border-gray-600 text-gray-300 hover:bg-gray-800">
                                    Salir
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        @if (session('success'))
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4">
                <div class="rounded-md bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4">
                <div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @isset($slot)
                {{ $slot }}
            @endisset
            @yield('content')
        </main>
    </div>
</body>

</html>
