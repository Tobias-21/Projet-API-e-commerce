<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       

        \App\Models\Category::factory(10)->create();

        User::factory(2)->state(['role' => 'vendor'])->create()->each(function ($vendor) {
            \App\Models\Product::factory(5)->create(['user_id' => $vendor->id])->each(function ($product) {
                $product->images()->saveMany(\App\Models\Product_Images::factory(3)->make());
                $product->variants()->saveMany(\App\Models\Product_Variant::factory(2)->make());
                $product->category()->associate(\App\Models\Category::inRandomOrder()->first())->save();
            });
        });

        User::factory(4)->state(['role' => 'admin'])->create()->each(function ($admin) {
            \App\Models\Product::factory(5)->create(['user_id' => $admin->id])->each(function ($product) {
                $product->images()->saveMany(\App\Models\Product_Images::factory(3)->make());
                $product->variants()->saveMany(\App\Models\Product_Variant::factory(2)->make());
                $product->category()->associate(\App\Models\Category::inRandomOrder()->first())->save();
            });
        });
       
        User::factory(10)->state(['role' => 'client'])->create();
    }
}
