<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Models\StockService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (Category::exists()) {
            return;
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@topapps.test'],
            [
                'name' => 'Admin TopApps',
                'password' => Hash::make('password'),
            ]
        );

        $streaming = Category::create(['name_category' => 'Streaming']);
        $productivity = Category::create(['name_category' => 'Productivity']);
        $gaming = Category::create(['name_category' => 'Gaming']);

        $netflix = Service::create([
            'category_id' => $streaming->id,
            'name_service' => 'Netflix Premium',
            'description_service' => 'Akun Netflix sharing 1 bulan',
            'image_service' => 'netflix.png',
            'stock_service' => 25,
            'price_service' => 35000,
        ]);

        $spotify = Service::create([
            'category_id' => $streaming->id,
            'name_service' => 'Spotify Family',
            'description_service' => 'Spotify premium family slot',
            'image_service' => 'spotify.png',
            'stock_service' => 18,
            'price_service' => 15000,
        ]);

        $canva = Service::create([
            'category_id' => $productivity->id,
            'name_service' => 'Canva Pro',
            'description_service' => 'Canva pro team invite',
            'image_service' => 'canva.png',
            'stock_service' => 4,
            'price_service' => 25000,
        ]);

        $office = Service::create([
            'category_id' => $productivity->id,
            'name_service' => 'Microsoft 365',
            'description_service' => 'Office 365 personal 1 tahun',
            'image_service' => 'office.png',
            'stock_service' => 12,
            'price_service' => 85000,
        ]);

        $steam = Service::create([
            'category_id' => $gaming->id,
            'name_service' => 'Steam Wallet 100K',
            'description_service' => 'Voucher steam 100 ribu',
            'image_service' => 'steam.png',
            'stock_service' => 3,
            'price_service' => 105000,
        ]);

        $movements = [
            [$netflix, 'in', 30, now()->subMonths(5)],
            [$netflix, 'out', 5, now()->subMonths(5)->addDays(3)],
            [$spotify, 'in', 20, now()->subMonths(4)],
            [$spotify, 'out', 2, now()->subMonths(4)->addDays(5)],
            [$canva, 'in', 10, now()->subMonths(3)],
            [$canva, 'out', 6, now()->subMonths(3)->addDays(2)],
            [$office, 'in', 15, now()->subMonths(2)],
            [$office, 'out', 3, now()->subMonths(2)->addDays(4)],
            [$steam, 'in', 8, now()->subMonth()],
            [$steam, 'out', 5, now()->subMonth()->addDays(2)],
            [$netflix, 'out', 3, now()->subDays(5)],
            [$spotify, 'in', 5, now()->subDays(2)],
        ];

        foreach ($movements as [$service, $type, $qty, $date]) {
            StockService::create([
                'service_id' => $service->id,
                'user_id' => $admin->id,
                'quantity' => $qty,
                'type' => $type,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
