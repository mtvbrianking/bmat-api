<?php

use App\User;
use Illuminate\Database\Seeder;
use Laravel\Passport\Client;
use Laravel\Passport\PersonalAccessClient;

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

        $personal_client = new Client();
        $personal_client->user_id = $user->id;
        $personal_client->name = 'dev-personal-client';
        $personal_client->secret = str_random('40');
        $personal_client->redirect = '';
        $personal_client->personal_access_client = true;
        $personal_client->password_client = false;
        $personal_client->revoked = false;
        $personal_client->save();

        $client = new \Laravel\Passport\PersonalAccessClient();
        $client->client_id = $personal_client->id;
        $client->save();

    }
}
