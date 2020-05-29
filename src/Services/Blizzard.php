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
    public function connection()
    {
        $tokenClient =  new Client([
            'base_uri'  =>  'https://eu.battle.net/oauth/token',
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