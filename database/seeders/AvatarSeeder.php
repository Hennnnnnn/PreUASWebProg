<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Avatar;
use Illuminate\Support\Facades\DB;

class AvatarSeeder extends Seeder
{
    public function run()
    {
        $avatars = [
            [
                'image' => file_get_contents(public_path('images/avatar1.png')),
                'price' => 10.00,
            ],
            [
                'image' => file_get_contents(public_path('images/avatar2.png')),
                'price' => 15.00,
            ],
            [
                'image' => file_get_contents(public_path('images/avatar3.png')),
                'price' => 20.00,
            ],
            [
                'image' => file_get_contents(public_path('images/avatar4.png')),
                'price' => 25.00,
            ],
        ];

        foreach ($avatars as $avatar) {
            Avatar::create($avatar);
        }
    }
}
