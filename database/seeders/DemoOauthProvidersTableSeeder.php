<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoOauthProvidersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('oauth_providers')->delete();
    }
}
