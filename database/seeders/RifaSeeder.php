<?php

namespace Database\Seeders;

use App\Models\Participacion;
use App\Models\Rifa;
use App\Models\User;
use Illuminate\Database\Seeder;

class RifaSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(25)->create();

        foreach ($this->rifasActivas() as $data) {
            Rifa::create($data);
        }

        $ganadorUsers = $users->take(4)->values();

        foreach ($this->rifasFinalizadas() as $i => $data) {
            $rifa = Rifa::create($data);

            $ganador = $ganadorUsers->get($i);
            Participacion::create([
                'rifa_id'            => $rifa->id,
                'user_id'            => $ganador->id,
                'nombre_participante' => $ganador->name,
                'numero'             => $data['numero_ganador'],
                'estado'             => 'confirmado',
            ]);

            $restantes = array_values(array_filter(
                range(1, $data['total_numeros']),
                fn ($n) => $n !== $data['numero_ganador']
            ));
            shuffle($restantes);

            foreach (array_slice($restantes, 0, 20) as $numero) {
                $user = $users->random();
                try {
                    Participacion::create([
                        'rifa_id'            => $rifa->id,
                        'user_id'            => $user->id,
                        'nombre_participante' => $user->name,
                        'numero'             => $numero,
                        'estado'             => 'confirmado',
                    ]);
                } catch (\Throwable) {
                    // Omitir duplicados
                }
            }
        }
    }

    private function rifasActivas(): array
    {
        return [
            [
                'nombre'           => 'iPhone 15 Pro Max 256GB',
                'descripcion'      => 'El último iPhone 15 Pro Max en color Titanio Natural. 256GB de almacenamiento, cámara de 48MP y chip A17 Pro.',
                'imagen'           => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&q=80',
                'precio_numero'    => 5000,
                'total_numeros'    => 100,
                'numeros_vendidos' => 73,
                'estado'           => 'activa',
                'monto_premio'     => 2500000,
                'premio_descripcion' => 'iPhone 15 Pro Max 256GB Titanio Natural',
                'fecha_sorteo'     => now()->addDays(12),
            ],
            [
                'nombre'           => 'MacBook Pro M3 14"',
                'descripcion'      => 'MacBook Pro con chip M3, 16GB RAM y 512GB SSD. El portátil más potente de Apple para profesionales.',
                'imagen'           => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&q=80',
                'precio_numero'    => 8000,
                'total_numeros'    => 80,
                'numeros_vendidos' => 36,
                'estado'           => 'activa',
                'monto_premio'     => 6500000,
                'premio_descripcion' => 'MacBook Pro 14" M3 16GB/512GB',
                'fecha_sorteo'     => now()->addDays(20),
            ],
            [
                'nombre'           => 'PlayStation 5 + 3 Juegos',
                'descripcion'      => 'Consola PlayStation 5 edición estándar más 3 juegos a elección del ganador.',
                'imagen'           => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=600&q=80',
                'precio_numero'    => 3000,
                'total_numeros'    => 150,
                'numeros_vendidos' => 89,
                'estado'           => 'activa',
                'monto_premio'     => 1800000,
                'premio_descripcion' => 'PS5 Edición Estándar + 3 juegos a elección',
                'fecha_sorteo'     => now()->addDays(7),
            ],
            [
                'nombre'           => 'Smart TV Samsung 65" QLED',
                'descripcion'      => 'Samsung Neo QLED 65 pulgadas 4K con Dolby Atmos, Smart TV y control por voz.',
                'imagen'           => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=600&q=80',
                'precio_numero'    => 4000,
                'total_numeros'    => 120,
                'numeros_vendidos' => 54,
                'estado'           => 'activa',
                'monto_premio'     => 3200000,
                'premio_descripcion' => 'Samsung Neo QLED 65" 4K 2024',
                'fecha_sorteo'     => now()->addDays(18),
            ],
            [
                'nombre'           => 'Bicicleta Eléctrica Premium',
                'descripcion'      => 'E-Bike de alta gama con autonomía de 80km, motor de 500W y batería de litio extraíble.',
                'imagen'           => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80',
                'precio_numero'    => 6000,
                'total_numeros'    => 60,
                'numeros_vendidos' => 23,
                'estado'           => 'activa',
                'monto_premio'     => 3800000,
                'premio_descripcion' => 'E-Bike 500W 80km de autonomía',
                'fecha_sorteo'     => now()->addDays(25),
            ],
            [
                'nombre'           => '💵 Efectivo $1.000.000',
                'descripcion'      => 'Un millón de pesos en efectivo, sin condiciones. ¡Tuyo para lo que quieras!',
                'imagen'           => null,
                'precio_numero'    => 10000,
                'total_numeros'    => 50,
                'numeros_vendidos' => 31,
                'estado'           => 'activa',
                'monto_premio'     => 1000000,
                'premio_descripcion' => '$1.000.000 en efectivo',
                'fecha_sorteo'     => now()->addDays(15),
            ],
        ];
    }

    private function rifasFinalizadas(): array
    {
        return [
            [
                'nombre'           => 'Viaje a Cartagena x2',
                'descripcion'      => 'Paquete todo incluido para 2 personas. 5 días y 4 noches en Cartagena de Indias.',
                'imagen'           => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=600&q=80',
                'precio_numero'    => 7000,
                'total_numeros'    => 80,
                'numeros_vendidos' => 80,
                'estado'           => 'finalizada',
                'monto_premio'     => 2800000,
                'premio_descripcion' => 'Viaje Cartagena todo incluido para 2',
                'fecha_sorteo'     => now()->subDays(5),
                'numero_ganador'   => 47,
            ],
            [
                'nombre'           => 'iPhone 14 Pro 128GB',
                'descripcion'      => 'iPhone 14 Pro en color Negro Espacial. 128GB de almacenamiento.',
                'imagen'           => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=600&q=80',
                'precio_numero'    => 4000,
                'total_numeros'    => 100,
                'numeros_vendidos' => 100,
                'estado'           => 'finalizada',
                'monto_premio'     => 2100000,
                'premio_descripcion' => 'iPhone 14 Pro 128GB Negro Espacial',
                'fecha_sorteo'     => now()->subDays(12),
                'numero_ganador'   => 83,
            ],
            [
                'nombre'           => 'Samsung Galaxy Tab S9',
                'descripcion'      => 'Samsung Galaxy Tab S9 con 256GB y S-Pen incluido.',
                'imagen'           => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&q=80',
                'precio_numero'    => 3500,
                'total_numeros'    => 80,
                'numeros_vendidos' => 80,
                'estado'           => 'finalizada',
                'monto_premio'     => 1400000,
                'premio_descripcion' => 'Samsung Galaxy Tab S9 256GB + S-Pen',
                'fecha_sorteo'     => now()->subDays(20),
                'numero_ganador'   => 12,
            ],
            [
                'nombre'           => 'AirPods Pro 2da Gen',
                'descripcion'      => 'Apple AirPods Pro segunda generación con cancelación activa de ruido.',
                'imagen'           => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=600&q=80',
                'precio_numero'    => 2000,
                'total_numeros'    => 80,
                'numeros_vendidos' => 80,
                'estado'           => 'finalizada',
                'monto_premio'     => 700000,
                'premio_descripcion' => 'AirPods Pro 2da Generación',
                'fecha_sorteo'     => now()->subDays(28),
                'numero_ganador'   => 65,
            ],
        ];
    }
}
