<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Post;
use App\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'role' => 2
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => Hash::make('secret'),
            'role' => 1
        ]);

        $category = Category::create([
            'tag' => 'Healthcare'
        ]);

        Category::create([
            'tag' => 'Jobs'
        ]);

        Category::create([
            'tag' => 'Electronics'
        ]);

        Category::create([
            'tag' => 'Programming'
        ]);

        Category::create([
            'tag' => 'Fantasy'
        ]);

        $post = Post::create([
            'user_id' => $admin->id,
            'title' => 'Title',
            'body' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus, laboriosam.',
        ]);

        $post->categories()->sync($category);
        $post->save();
    }
}
