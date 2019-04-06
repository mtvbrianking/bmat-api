<?php

namespace Tests;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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
     * Create client credentials grant client app.
     *
     * @return void
     */
    private function createClient()
    {
        $this->client = new \Laravel\Passport\Client();
        $this->client->user_id = null;
        $this->client->name = 'test-client-grant-client';
        $this->client->secret = str_random('40');
        $this->client->redirect = '';
        $this->client->personal_access_client = false;
        $this->client->password_client = false;
        $this->client->revoked = false;
        $this->client->save();
    }

    /**
     * Create password grant client app.
     *
     * @return void
     */
    public function createPasswordClient()
    {
        $this->client = new \Laravel\Passport\Client();
        $this->client->user_id = null;
        $this->client->name = 'test-password-grant-client';
        $this->client->secret = str_random('40');
        $this->client->redirect = '';
        $this->client->personal_access_client = false;
        $this->client->password_client = true;
        $this->client->revoked = false;
        $this->client->save();
    }

    /**
     * Request client credentials grant access token.
     *
     * @param array $scopes
     *
     * @return array token
     */
    protected function getClientToken($scopes = [])
    {
        $this->createClient();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->call('POST', 'oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => implode(' ', $scopes),
        ]);

        return json_decode((string) $response->getContent(), true);
    }

    /**
     * Request password grant access token.
     *
     * @param string $username
     * @param string $password
     * @param array $scopes
     *
     * @return array token
     */
    protected function getPasswordToken($username, $password, $scopes = [])
    {
        $this->createPasswordClient();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->call('POST', 'oauth/token', [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $username,
            'password' => $password,
            'scope' => implode(' ', $scopes),
        ]);

        return json_decode((string) $response->getContent(), true);
    }
}
