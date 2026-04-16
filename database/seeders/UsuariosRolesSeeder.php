<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuariosRolesSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuario ADMIN ────────────────────────── 
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@tickets.com',
            'password' => Hash::make('password'),
            'rol'      => 'admin',
        ]);
        // ── Usuario GERENTE ──────────────────────── 
        User::create([
            'name'     => 'Gerente General',
            'email'    => 'gerente@tickets.com',
            'password' => Hash::make('password'),
            'rol'      => 'gerente',
        ]);
        // ── Usuario REGULAR ─────────────────────── 
        User::create([
            'name'     => 'Juan Pérez',
            'email'    => 'usuario@tickets.com',
            'password' => Hash::make('password'),
            'rol'      => 'usuario',
        ]);
    }
}
