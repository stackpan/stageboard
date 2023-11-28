<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Database\Seeder;
use \App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //         User::factory(10)->create();

         $user = User::factory()
            ->hasBoards(2)
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
             ]);

         foreach ($user->boards as $board) {
            $columnNames = ['Done', 'In Progress', 'Open'];
             for ($i = 0; $i < count($columnNames); $i++) {
                 Column::factory()
                    ->for($board)
                    ->hasCards(3)
                    ->create([
                        'name' => $columnNames[$i],
                        'order' => $i,
                    ]);
             }
         }
    }
}
