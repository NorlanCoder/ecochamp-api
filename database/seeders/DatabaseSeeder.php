<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Tag;
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

        $user = User::factory()->create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'phone_number' => '+22960987654',
            'country' => 'Benin',
            'city' => 'Cotonou',
            'gender' => 'masculin',
        ]);

        $alert = ['feu', 'vent', 'foret'];
        $medias = ['post/image1.jpg', 'post/image5.jpg'];
        
        $post = Post::create([
            'user_id'       => $user->id,
            'title'         => 'Test Alerte',
            'message'       => 'Feu de foret',
            'country'       => 'Benin',
            'city'          => 'Godomey',
            'distributed_to'=> 'all',
            'type'          => 'alerte',
            'status'        => '',
        ]);
        
        foreach ($alert as $label) {
            $new_tag = Tag::where('label', $label)->first();
            if(!$new_tag){
                $new_tag = Tag::create(['label' => $label, 'count' => 0]);
            }
            $new_tag->increment('count');
            $new_tag->save();
        }
        
        foreach ($medias as $image) {
            $media = Media::create([
                'url_media' => $image,
            ]);
            PostMedia::create([
                'post_id'  => $post->id,
                'media_id' => $media->id,
            ]);
        }
        
        // Deuxième alerte
        $alert = ['terrain', 'sèche'];
        $medias = ['post/image2.jpg', 'post/image4.jpg'];
        
        $post = Post::create([
            'user_id'       => $user->id,
            'title'         => 'Test Alerte',
            'message'       => 'Terrain sèche',
            'country'       => 'Benin',
            'city'          => 'Godomey',
            'distributed_to'=> 'all',
            'type'          => 'alerte',
            'status'        => '',
        ]);
        
        foreach ($alert as $label) {
            $new_tag = Tag::where('label', $label)->first();
            if(!$new_tag){
                $new_tag = Tag::create(['label' => $label, 'count' => 0]);
            }
            $new_tag->increment('count');
            $new_tag->save();
        }
        
        foreach ($medias as $image) {
            $media = Media::create([
                'url_media' => $image,
            ]);
            PostMedia::create([
                'post_id'  => $post->id,
                'media_id' => $media->id,
            ]);
        }

    }        
}
