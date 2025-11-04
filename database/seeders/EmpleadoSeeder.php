<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleado;
use Illuminate\Support\Facades\Hash;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Empleado::firstOrCreate(
            
            ['email' => 'admin@biblioteca.com'],
            // Si no lo encuentra, lo crea con estos datos
            [
                'nombre' => 'Admin',
                'password' => Hash::make('alpha123456789'),
                'rol' => 'admin'
            ]
        );
    }
}
