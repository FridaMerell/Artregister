<?php

namespace App\Service\Artportalen\Taxonomies;

use phpDocumentor\Reflection\Types\This;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * API Integration for Artfakta
 * https://www.artdatabanken.se/sok-art-och-miljodata/oppna-data-och-apier/om-artdatabankens-apier/api-for-artinformation
 */
class ArtFakta {
	const API_ENDPOINT = 'https://api.artdatabanken.se/information/v1/speciesdataservice/v1/speciesdata?taxa={taxa}';
	const API_GETTEXT = 'https://api.artdatabanken.se/information/v1/speciesdataservice/v1/speciesdata/texts?taxa={taxa}';
	const API_SUBSTRATES = 'https://api.artdatabanken.se/information/v1/speciesdataservice/v1/speciesdata/substrates?taxa={taxa}';
	/** @var int $taxa */
	private $taxa;

	function __construct(private readonly ContainerBagInterface $apisecret, private readonly HttpClientInterface $webclient){
	}

	function setTaxa(int $taxa): void{
		$this->taxa = $taxa;
	}

	function getHeaders(): array{
		return ['Ocp-Apim-Subscription-Key' => $this->apisecret];
	}

	function prepareEndpoint(string $endpoint): ?string{
		return str_replace('{taxa}', $this->taxa, $endpoint);

		return null;
	}

	function getData(){
		if (empty($this->taxa))
			return null;

		$response = $this->webclient->request(
			'GET',
			$this->prepareEndpoint(self::API_ENDPOINT),
			['headers' => $this->getHeaders()]
		);

		$content = $response->getContent();
		return json_decode($content);
	}

	function getSubstrates(): ?object{
		if (empty($this->taxa))
			return null;

		$response = $this->webclient->request(
			'GET',
			$this->prepareEndpoint(self::API_SUBSTRATES),
			['headers' => $this->getHeaders()]
		);

		$content = $response->getContent();

		return json_decode($content);
	}

	function getTexts(){
		if (empty($this->taxa))
			return null;

		$response = $this->webclient->request(
			'GET',
			$this->prepareEndpoint(self::API_GETTEXT),
			['headers' => $this->getHeaders()]
		);
		$content = $response->getContent();
		return json_decode($content);
	}
}
