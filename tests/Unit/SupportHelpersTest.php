<?php

namespace Tests\Unit;

use Tests\TestCase;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupportHelpersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Can get client from request.
     *
     * Client credentials grant type application.
     *
     * @test
     * @group passing
     */
    public function can_get_auth_client()
    {
        $token = $this->getClientToken();

        // Symfony\Component\HttpFoundation\Request@create
        $request = Request::create('api/v1', 'POST', []);

        $request->headers->add([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$token['access_token'],
        ]);

        $bearerToken = $request->bearerToken();

        $this->assertEquals($bearerToken, $token['access_token']);

        $tokenId = (new Parser())->parse($bearerToken)->getHeader('jti');

        $client = Token::find($tokenId)->client;

        $this->assertInstanceOf(Client::class, $client);
    }
}
