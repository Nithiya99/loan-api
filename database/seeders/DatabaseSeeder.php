<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        for($i=0; $i<5; $i++){
            DB::table('users')->insert([
                'user_id'=>\Ramsey\Uuid\Uuid::uuid4()->toString(),
                'name'=>Str::random(15),
                'user_type'=>"Client",
                'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
        for($i=0; $i<2; $i++){
            DB::table('users')->insert([
                'user_id'=>\Ramsey\Uuid\Uuid::uuid4()->toString(),
                'name'=>Str::random(15),
                'user_type'=>"Admin",
                'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
