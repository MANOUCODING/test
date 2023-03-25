<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        $this->call([RoleTableSeeder::class]);

        $this->call([UserTableSeeder::class]);

        $this->call([CategoryTableSeeder::class]);

        $this->call([AuthorTableSeeder::class]);

        $this->call([TagsTableSeeder::class]);

        $this->call([FileTableSeeder::class]);

        $this->call([ArticleTableSeeder::class]);
    }
}
