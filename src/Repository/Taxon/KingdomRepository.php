<?php

namespace App\Repository\Taxon;

use App\Entity\Taxon\Kingdom;
use App\Entity\Taxon\Species;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kingdom>
 *
 * @method Kingdom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Kingdom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Kingdom[]    findAll()
 * @method Kingdom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KingdomRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Kingdom::class);
    }

    public function add(Kingdom $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Kingdom $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // public function findSpecies(Kingdom $entity) {
    //     return $this->createNativeQuery("SELECT species.*, genus.family_id, family.tax_order_id,
    //     artdata.order.class_id, tax_class.strain_id, strain.kingdom_id,
    //     kingdom.id FROM species
    //     LEFT JOIN genus ON species.genus_id = genus.id
    //     LEFT JOIN family ON genus.family_id = family.id
    //     LEFT JOIN artdata.order ON family.tax_order_id = artdata.order.id
    //     LEFT JOIN tax_class ON artdata.order.class_id = tax_class.id
    //     LEFT JOIN strain ON tax_class.strain_id = strain.id
    //     LEFT JOIN kingdom ON strain.kingdom_id = kingdom.id
    //     WHERE kingdom.id = {$entity->getId()};
    //     ")->getResult();
    // }

    //    /**
    //     * @return Kingdom[] Returns an array of Kingdom objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('k')
    //            ->andWhere('k.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('k.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Kingdom
    //    {
    //        return $this->createQueryBuilder('k')
    //            ->andWhere('k.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
