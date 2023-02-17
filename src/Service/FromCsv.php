<?php

namespace App\Service;

use App\Entity\Taxon\Family;
use App\Entity\Taxon\Genus;
use App\Entity\Taxon\Kingdom;
use App\Entity\Taxon\Order;
use App\Entity\Taxon\OrganismGroup;
use App\Entity\Taxon\Species;
use App\Entity\Taxon\Strain;
use App\Entity\Taxon\TaxClass;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FromCsv
{
    /**
     * doctrine
     *
     * @var \Doctrine\ORM\EntityManager doctrine
     */
    private EntityManagerInterface|EntityManager $doctrine;
    private ValidatorInterface $validator;
    private CsvEncoder $encoder;
    private $fileContent;

    function __construct(EntityManagerInterface $doctrine, ValidatorInterface $validator)
    {
        $this->doctrine = $doctrine;
        $this->encoder = new CsvEncoder();
        $this->validator = $validator;
    }


    function setFile(string $file)
    {
        $this->fileContent = file_get_contents($file);
        //dd($this->fileContent);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    function handleFile()
    {
        $data = $this->encoder->decode($this->fileContent, 'csv', [CsvEncoder::DELIMITER_KEY => ';']);
        foreach ($data as $taxonomy) {
            if (empty($taxonomy['Taxon id'])) continue;
            $entityName = $this->getEntity($taxonomy['Taxonkategori']);

            if (!$entityName)
                continue;
            $repo = $this->doctrine->getRepository($entityName);
            if ($repo->findOneBy(array('TaxonomyId' => $taxonomy['Taxon id'])))
                continue;

            $entity = new $entityName;
            $entity->setTaxonomyId($taxonomy['Taxon id']);
            $entity->setVernacularName($taxonomy['Svenskt namn'] ?? null);
            $entity->setScientificName($taxonomy['Vetenskapligt namn']);


            if (method_exists($entity, 'setClass'))
                $entity->setClass($this->getRelatedEntity(TaxClass::class, $taxonomy['Klass']));

            if (method_exists($entity, 'setTaxOrder'))
                $entity->setTaxOrder($this->getRelatedEntity(Order::class, $taxonomy['Ordning']));

            if (method_exists($entity, 'setFamily'))
                $entity->setFamily($this->getRelatedEntity(Family::class, $taxonomy['Familj']));

            if (method_exists($entity, 'setGenus'))
                $entity->setGenus($this->getRelatedEntity(Genus::class, $taxonomy['Släkte']));


            $errors = $this->validator->validate($entity);
            if (count($errors) == 0) {
                $this->doctrine->persist($entity);
                $this->doctrine->flush();
            }
        }
    }

    function getEntity($name): ?string
    {

        return match ($name) {
            'Klass' => TaxClass::class,
            'Ordning' => Order::class,
            'Familj' => Family::class,
            'Släkte' => Genus::class,
            'Art' => Species::class,
            default => null,
        };
    }

    function getRelatedEntity($entityName, $searchTerm)
    {
        $repository = $this->doctrine->getRepository($entityName);
        return $repository->findOneBy(['ScientificName' => $searchTerm]);
    }
}
