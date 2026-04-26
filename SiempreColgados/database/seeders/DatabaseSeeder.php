<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Incidencia;
use App\Models\Cuota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder principal - Inserta datos de prueba completos.
 *
 * Usuarios creados:
 *   admin@test.com  / password  → Administrador
 *   juan@test.com   / password  → Operario
 *   maria@test.com  / password  → Operario
 *
 * Ejecutar con: php artisan db:seed
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Inserta los datos de prueba en la base de datos.
     *
     * @return void
     */
    public function run(): void
    {
        // ----------------------------------------------------------------
        // USUARIOS Y EMPLEADOS
        // ----------------------------------------------------------------
        $uAdmin = User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $admin = Empleado::create([
            'dni' => '00000001A',
            'nombre' => 'Administrador Principal',
            'correo' => 'admin@test.com',
            'telefono' => '959000001',
            'direccion' => 'C/ Administración 1, Huelva',
            'fecha_alta' => '2020-01-01',
            'tipo' => 'administrador',
            'activo' => true,
            'user_id' => $uAdmin->id,
        ]);

        $uJuan = User::create([
            'name'     => 'Juan García',
            'email'    => 'juan@test.com',
            'password' => Hash::make('password'),
        ]);
        $juan = Empleado::create([
            'dni' => '00000002B',
            'nombre' => 'Juan García López',
            'correo' => 'juan@test.com',
            'telefono' => '666111001',
            'fecha_alta' => '2021-03-15',
            'tipo' => 'operario',
            'activo' => true,
            'user_id' => $uJuan->id,
        ]);

        $uMaria = User::create([
            'name'     => 'María Fernández',
            'email'    => 'maria@test.com',
            'password' => Hash::make('password'),
        ]);
        $maria = Empleado::create([
            'dni' => '00000003C',
            'nombre' => 'María Fernández Ruiz',
            'correo' => 'maria@test.com',
            'telefono' => '666111002',
            'fecha_alta' => '2022-06-01',
            'tipo' => 'operario',
            'activo' => true,
            'user_id' => $uMaria->id,
        ]);

        // ----------------------------------------------------------------
        // CLIENTES
        // ----------------------------------------------------------------
        $c1 = Cliente::create([
            'cif' => 'B11111111',
            'nombre' => 'Comercial Huelva S.L.',
            'telefono' => '959100001',
            'correo' => 'comercial@huelva.com',
            'cuenta_corriente' => 'ES0000000000000000000001',
            'pais' => 'ES',
            'moneda' => 'EUR',
            'importe_cuota' => 150.00,
            'activo' => true,
        ]);
        $c2 = Cliente::create([
            'cif' => 'B22222222',
            'nombre' => 'Tech Solutions Ltd.',
            'telefono' => '959100002',
            'correo' => 'info@techsolutions.uk',
            'cuenta_corriente' => 'GB0000000000000000000002',
            'pais' => 'GB',
            'moneda' => 'GBP',
            'importe_cuota' => 130.00,
            'activo' => true,
        ]);
        $c3 = Cliente::create([
            'cif' => 'B33333333',
            'nombre' => 'Edificios Madrid S.A.',
            'telefono' => '917000001',
            'correo' => 'edificios@madrid.com',
            'cuenta_corriente' => 'ES0000000000000000000003',
            'pais' => 'ES',
            'moneda' => 'EUR',
            'importe_cuota' => 200.00,
            'activo' => true,
        ]);

        // ----------------------------------------------------------------
        // INCIDENCIAS
        // ----------------------------------------------------------------
        Incidencia::create([
            'cliente_id' => $c1->id,
            'operario_id' => $juan->id,
            'persona_contacto' => 'Pedro Sánchez',
            'telefono_contacto' => '666222001',
            'descripcion' => 'Ascensor bloqueado en planta 3, la puerta no cierra',
            'correo' => 'pedro@comercial.com',
            'direccion' => 'C/ Principal 10',
            'poblacion' => 'Huelva',
            'codigo_postal' => '21001',
            'provincia_codigo' => '21',
            'estado' => 'P',
            'fecha_realizacion' => now()->addDays(2)->toDateString(),
            'anotaciones_anteriores' => 'Cliente indica que lleva así desde ayer',
        ]);

        Incidencia::create([
            'cliente_id' => $c1->id,
            'operario_id' => $maria->id,
            'persona_contacto' => 'Ana Martínez',
            'telefono_contacto' => '666222002',
            'descripcion' => 'Revisión anual obligatoria del ascensor principal',
            'correo' => 'ana@comercial.com',
            'direccion' => 'C/ Principal 10',
            'poblacion' => 'Huelva',
            'codigo_postal' => '21001',
            'provincia_codigo' => '21',
            'estado' => 'R',
            'fecha_realizacion' => now()->subDays(5)->toDateString(),
            'anotaciones_posteriores' => 'Revisión completada. Todo correcto. Certificado expedido.',
        ]);

        Incidencia::create([
            'cliente_id' => $c2->id,
            'operario_id' => $juan->id,
            'persona_contacto' => 'James Smith',
            'telefono_contacto' => '+44666333001',
            'descripcion' => 'Elevator stuck between floors 2 and 3 - URGENT',
            'correo' => 'james@techsolutions.uk',
            'estado' => 'P',
            'fecha_realizacion' => now()->addDay()->toDateString(),
            'anotaciones_anteriores' => 'URGENTE: personas dentro del ascensor',
        ]);

        Incidencia::create([
            'cliente_id' => $c3->id,
            'operario_id' => $maria->id,
            'persona_contacto' => 'Carmen López',
            'telefono_contacto' => '917000002',
            'descripcion' => 'Ruido metálico excesivo en el motor del ascensor',
            'correo' => 'carmen@edificios.com',
            'direccion' => 'Av. Principal 100',
            'poblacion' => 'Madrid',
            'codigo_postal' => '28001',
            'provincia_codigo' => '28',
            'estado' => 'P',
            'fecha_realizacion' => now()->addDays(4)->toDateString(),
        ]);

        // Sin operario (registrada por el cliente vía formulario público)
        Incidencia::create([
            'cliente_id' => $c3->id,
            'operario_id' => null,
            'persona_contacto' => 'Luis Gómez',
            'telefono_contacto' => '917000003',
            'descripcion' => 'Botón del piso 7 no responde, hay que pulsar varias veces',
            'correo' => 'luis@edificios.com',
            'estado' => 'P',
        ]);

        // ----------------------------------------------------------------
        // CUOTAS
        // ----------------------------------------------------------------
        Cuota::create([
            'cliente_id' => $c1->id,
            'concepto' => 'Cuota mensual mantenimiento - Enero 2024',
            'fecha_emision' => '2024-01-01',
            'importe' => 150.00,
            'moneda' => 'EUR',
            'pagada' => true,
            'fecha_pago' => '2024-01-15',
            'importe_euros' => 150.00,
            'tipo_cambio' => 1.000000,
        ]);
        Cuota::create([
            'cliente_id' => $c1->id,
            'concepto' => 'Cuota mensual mantenimiento - Febrero 2024',
            'fecha_emision' => '2024-02-01',
            'importe' => 150.00,
            'moneda' => 'EUR',
            'pagada' => false,
        ]);
        Cuota::create([
            'cliente_id' => $c2->id,
            'concepto' => 'Monthly maintenance fee - January 2024',
            'fecha_emision' => '2024-01-01',
            'importe' => 130.00,
            'moneda' => 'GBP',
            'pagada' => true,
            'fecha_pago' => '2024-01-20',
            'importe_euros' => 151.58,
            'tipo_cambio' => 1.166000,
        ]);
        Cuota::create([
            'cliente_id' => $c2->id,
            'concepto' => 'Monthly maintenance fee - February 2024',
            'fecha_emision' => '2024-02-01',
            'importe' => 130.00,
            'moneda' => 'GBP',
            'pagada' => false,
        ]);
        Cuota::create([
            'cliente_id' => $c3->id,
            'concepto' => 'Cuota mensual mantenimiento - Enero 2024',
            'fecha_emision' => '2024-01-01',
            'importe' => 200.00,
            'moneda' => 'EUR',
            'pagada' => true,
            'fecha_pago' => '2024-01-10',
            'importe_euros' => 200.00,
            'tipo_cambio' => 1.000000,
        ]);
        Cuota::create([
            'cliente_id' => $c3->id,
            'concepto' => 'Trabajo excepcional: sustitución cable de tracción',
            'fecha_emision' => '2024-02-15',
            'importe' => 850.00,
            'moneda' => 'EUR',
            'pagada' => false,
            'notas' => 'Reparación urgente. Piezas y mano de obra incluidas.',
        ]);

        $this->command->info('✓ Base de datos cargada correctamente.');
        $this->command->info('  → admin@test.com / password');
        $this->command->info('  → juan@test.com  / password');
        $this->command->info('  → maria@test.com / password');
    }
}
