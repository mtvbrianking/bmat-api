<?php

if (! function_exists('get_auth_client')) {
    /**
     * Get authenticated client application.
     *
     * @link https://github.com/laravel/passport/issues/124#issuecomment-252434309
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    function get_auth_client($request)
    {
        $bearerToken = $request->bearerToken();
        $tokenId = (new \Lcobucci\JWT\Parser())->parse($bearerToken)->getHeader('jti');

        return \Laravel\Passport\Token::find($tokenId)->client;
    }
}
