<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Boss (Admin)',
            'email' => 'admin@vyg.pl',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Doe (Waiter)',
            'email' => 'waiter@vyg.pl',
            'password' => Hash::make('password123'),
            'role' => 'waiter',
        ]);

        User::create([
            'name' => 'Gordon Ramsay (Cook)',
            'email' => 'cook@vyg.pl',
            'password' => Hash::make('password123'),
            'role' => 'cook',
        ]);

        // Tables
        $tables = [
            ['number' => 'Table 1', 'capacity' => 2],
            ['number' => 'Table 2', 'capacity' => 2],
            ['number' => 'Table 3', 'capacity' => 4],
            ['number' => 'Table 4', 'capacity' => 4],
            ['number' => 'Table 5', 'capacity' => 6],
            ['number' => 'VIP Table', 'capacity' => 8],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }

        // Categories
        $catMain = Category::create(['name' => 'Main Courses']);
        $catDrinks = Category::create(['name' => 'Drinks']);
        $catDesserts = Category::create(['name' => 'Desserts']);

        // Main Courses
        MenuItem::create(['category_id' => $catMain->id, 'name' => 'Pork Chop with Potatoes', 'price' => 35.00]);
        MenuItem::create(['category_id' => $catMain->id, 'name' => 'Margherita Pizza', 'price' => 32.00]);
        MenuItem::create(['category_id' => $catMain->id, 'name' => 'Beef Burger', 'price' => 38.00]);
        MenuItem::create(['category_id' => $catMain->id, 'name' => 'Pasta Carbonara', 'price' => 34.00]);

        // Drinks
        MenuItem::create(['category_id' => $catDrinks->id, 'name' => 'Coca-Cola 0.5l', 'price' => 9.00]);
        MenuItem::create(['category_id' => $catDrinks->id, 'name' => 'Still Water', 'price' => 6.00]);
        MenuItem::create(['category_id' => $catDrinks->id, 'name' => 'Draft Beer 0.5l', 'price' => 15.00]);
        MenuItem::create(['category_id' => $catDrinks->id, 'name' => 'Orange Juice', 'price' => 8.00]);

        // Desserts
        MenuItem::create(['category_id' => $catDesserts->id, 'name' => 'Homemade Cheesecake', 'price' => 18.00]);
        MenuItem::create(['category_id' => $catDesserts->id, 'name' => 'Artisan Ice Cream', 'price' => 16.00]);
    }
}
