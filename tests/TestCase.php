<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var \Laravel\Passport\Client
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        // Load test keys
        Passport::loadKeysFrom(__DIR__.'/storage');
    }

    /**
     * Create client app
     * @return void
     */
    private function createClient()
    {
        $this->client = new \Laravel\Passport\Client();
        $this->client->user_id = null;
        $this->client->name = 'dev-client-grant-client';
        $this->client->secret = str_random('40');
        $this->client->redirect = '';
        $this->client->personal_access_client = false;
        $this->client->password_client = false;
        $this->client->revoked = false;
        $this->client->save();
    }

    /**
     * Request client app access token.
     *
     * @return string access token
     */
    protected function getClientToken()
    {
        $this->createClient();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            // 'Authorization' => 'Basic '.base64_encode($client->id.':'.$client->secret),
        ])->call('POST', 'oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => '',
        ]);

        return json_decode((string) $response->getContent(), true)['access_token'];
    }
}
