<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = User::create([
            'name' => 'Test user',
            'email' => 'test@laravel-test.com',
            'password' => Hash::make('password')
        ]);

        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $user->id,
            'name' => 'test token',
            'token' => '57317a758dfec13253dce56e84e8dc709d725734723d6895853cf9d83ffd7e86', //1|L2uIeTHjPFfE2kkg8zuGFwdAximpdI6nCLaRU9Hz
            'abilities' => "[\"*\"]"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::find()->where('email', 'test@laravel-test.com')->delete();
    }
};
