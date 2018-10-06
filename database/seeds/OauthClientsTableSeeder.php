<?php

use App\User;
use Illuminate\Database\Seeder;
use Laravel\Passport\Client;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default user

        $user = User::first();

        // Client credentials grant client

        $client = new Client();
        $client->user_id = null;
        $client->name = 'dev-client-grant-client';
        $client->secret = str_random('40');
        $client->redirect = '';
        $client->personal_access_client = false;
        $client->password_client = false;
        $client->revoked = false;
        $client->save();

        // Password grant client

        $client = new Client();
        $client->user_id = null;
        $client->name = 'dev-password-grant-client';
        $client->secret = str_random('40');
        $client->redirect = '';
        $client->personal_access_client = false;
        $client->password_client = true;
        $client->revoked = false;
        $client->save();

        // Personal access token client

        $client = new Client();
        $client->user_id = $user->id;
        $client->name = 'dev-personal-client';
        $client->secret = str_random('40');
        $client->redirect = '';
        $client->personal_access_client = true;
        $client->password_client = false;
        $client->revoked = false;
        $client->save();

    }
}
