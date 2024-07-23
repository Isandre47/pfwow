<?php
/**
 * Copyright (c) 2020.
 * Created by PhpStorm.
 * User: Isandre47
 * Date: 29/05/2020 16:46
 */

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use kamermans\OAuth2\GrantType\ClientCredentials;
use kamermans\OAuth2\OAuth2Middleware;

class Blizzard
{
    const TOKEN_EU = 'https://eu.battle.net/oauth/token';

    const HOST_US = 'https://us.api.blizzard.com/';
    const HOST_EU = 'https://eu.api.blizzard.com/';
    const HOST_KR = 'https://kr.api.blizzard.com/';
    const HOST_TW = 'https://tw.api.blizzard.com/';

    public function connection()
    {
        $tokenClient =  new Client([
            'base_uri'  =>  self::TOKEN_EU,
        ]);
        $tokenConfig = [
            'client_id'     =>  $_ENV['CLIENT_ID'],
            'client_secret' =>  $_ENV['CLIENT_SECRET'],
        ];

        $grant_type = new ClientCredentials($tokenClient, $tokenConfig);
        $oauth = new OAuth2Middleware($grant_type);
        $stack = HandlerStack::create();
        $stack->push($oauth);

        return $client = new Client([
            'handler'   =>  $stack,
            'auth'      =>  'oauth',
        ]);
    }
}