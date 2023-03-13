<?php

namespace App\Service;

use App\Entity\Card;
use App\Repository\Taxon\SpeciesRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Mime\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class NewCard {
	private string $templatePath;
	private CsvEncoder $encoder;

	function __construct(
		private readonly SpeciesRepository $repository,
		private readonly UserRepository    $userRepository
	){
		$this->encoder = new CsvEncoder();
	}

	function setTemplate(string $template): void{
		$sd = scandir(__DIR__ . '/../CardTemplates');
		$template = strtolower($template);
		foreach ($sd as $file) {
			$path = __DIR__ . '/../CardTemplates/' . $file;
			if (str_contains($file, $template)) {
				$this->templatePath = $path;
			}
		}
	}

	function getTemplateContent(): ?string{
		if (!isset($this->templatePath)) return null;
		$content = file_get_contents($this->templatePath);
		return $content;
	}

	function addSpecies(Card &$card): bool{
		$content = $this->getTemplateContent();
		if (!$content) return false;
		$data = $this->encoder->decode($content, 'csv');

		foreach ($data as $line) {
			$name = $line['Name'];
			if (!$name) continue;
			$species = $this->repository->findOneBy(['VernacularName' => strtolower($name)]);
			if (!$species) continue;

			$card->addSpecies($species);
		}

		return true;
	}

	function addUsers(Card &$card, ArrayCollection $users): bool{
		foreach ($users as $user) {
			$card->addSubscriber($user);
		}

		return true;
	}
}