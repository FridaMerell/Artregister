<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class Observations {
	private $client;
	private $apiSecret;

	function __construct($apiSecret, HttpClient $client){
		$this->client = $client;
		$this->apiSecret = $apiSecret;
	}

	function fetchById(int $id){
	}
}
