<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(Masters\AdminsTableSeeder::class);
        $this->call(Masters\BannersTableSeeder::class);
        $this->call(Masters\BannerBlockContentsTableSeeder::class);
        $this->call(Masters\BannerBlocksTableSeeder::class);
        $this->call(Masters\CoinsTableSeeder::class);
        $this->call(Masters\EventsTableSeeder::class);
        $this->call(Masters\HomeContentsGroupsTableSeeder::class);
        $this->call(Masters\HomeContentsTableSeeder::class);
        $this->call(Masters\InformationsTableSeeder::class);
        $this->call(Masters\ManufacturersTableSeeder::class);
        $this->call(Masters\ProductsTableSeeder::class);
        $this->call(Masters\QuestionnairesTableSeeder::class);
        $this->call(Masters\ServiceTermsTableSeeder::class);
    }
}
