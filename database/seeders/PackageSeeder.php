<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'name' => 'Paket Internet 10GB',
            'provider' => 'Telkomsel',
            'quota' => '10GB',
            'price' => 75000,
            'validity_days' => 30,
            'description' => 'Paket internet 10GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet Unlimited',
            'provider' => 'Indosat',
            'quota' => 'Unlimited',
            'price' => 100000,
            'validity_days' => 30,
            'description' => 'Paket internet unlimited 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 5GB',
            'provider' => 'XL',
            'quota' => '5GB',
            'price' => 50000,
            'validity_days' => 30,
            'description' => 'Paket internet 5GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 20GB',
            'provider' => 'Telkomsel',
            'quota' => '20GB',
            'price' => 150000,
            'validity_days' => 30,
            'description' => 'Paket internet 20GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 3GB',
            'provider' => 'Tri',
            'quota' => '3GB',
            'price' => 35000,
            'validity_days' => 30,
            'description' => 'Paket internet 3GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 8GB',
            'provider' => 'Smartfren',
            'quota' => '8GB',
            'price' => 65000,
            'validity_days' => 30,
            'description' => 'Paket internet 8GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 15GB',
            'provider' => 'Indosat',
            'quota' => '15GB',
            'price' => 120000,
            'validity_days' => 30,
            'description' => 'Paket internet 15GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 7GB',
            'provider' => 'Axis',
            'quota' => '7GB',
            'price' => 55000,
            'validity_days' => 30,
            'description' => 'Paket internet 7GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 25GB',
            'provider' => 'XL',
            'quota' => '25GB',
            'price' => 180000,
            'validity_days' => 30,
            'description' => 'Paket internet 25GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 12GB',
            'provider' => 'Telkomsel',
            'quota' => '12GB',
            'price' => 90000,
            'validity_days' => 30,
            'description' => 'Paket internet 12GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 6GB',
            'provider' => 'Tri',
            'quota' => '6GB',
            'price' => 45000,
            'validity_days' => 30,
            'description' => 'Paket internet 6GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Paket Internet 30GB',
            'provider' => 'Indosat',
            'quota' => '30GB',
            'price' => 200000,
            'validity_days' => 30,
            'description' => 'Paket internet 30GB valid 30 hari',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Telkomsel 5K',
            'provider' => 'Telkomsel',
            'quota' => '5000',
            'price' => 5000,
            'validity_days' => 30,
            'description' => 'Pulsa Telkomsel nominal 5000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Telkomsel 10K',
            'provider' => 'Telkomsel',
            'quota' => '10000',
            'price' => 10000,
            'validity_days' => 30,
            'description' => 'Pulsa Telkomsel nominal 10000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Telkomsel 20K',
            'provider' => 'Telkomsel',
            'quota' => '20000',
            'price' => 20000,
            'validity_days' => 30,
            'description' => 'Pulsa Telkomsel nominal 20000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Indosat 5K',
            'provider' => 'Indosat',
            'quota' => '5000',
            'price' => 5000,
            'validity_days' => 30,
            'description' => 'Pulsa Indosat nominal 5000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Indosat 10K',
            'provider' => 'Indosat',
            'quota' => '10000',
            'price' => 10000,
            'validity_days' => 30,
            'description' => 'Pulsa Indosat nominal 10000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa XL 5K',
            'provider' => 'XL',
            'quota' => '5000',
            'price' => 5000,
            'validity_days' => 30,
            'description' => 'Pulsa XL nominal 5000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa XL 15K',
            'provider' => 'XL',
            'quota' => '15000',
            'price' => 15000,
            'validity_days' => 30,
            'description' => 'Pulsa XL nominal 15000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Tri 5K',
            'provider' => 'Tri',
            'quota' => '5000',
            'price' => 5000,
            'validity_days' => 30,
            'description' => 'Pulsa Tri nominal 5000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Smartfren 10K',
            'provider' => 'Smartfren',
            'quota' => '10000',
            'price' => 10000,
            'validity_days' => 30,
            'description' => 'Pulsa Smartfren nominal 10000 rupiah',
            'is_active' => true,
        ]);

        Package::create([
            'name' => 'Pulsa Axis 5K',
            'provider' => 'Axis',
            'quota' => '5000',
            'price' => 5000,
            'validity_days' => 30,
            'description' => 'Pulsa Axis nominal 5000 rupiah',
            'is_active' => true,
        ]);
    }
}
