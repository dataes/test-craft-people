<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository implements CardRepositoryInterface
{
    /**
     * @var EntityManager|EntityManagerInterface
     */
    private $entityManager;

    /**
     * CardRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * @param Card $card
     * @return Card
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Card $card): Card
    {
        $this->entityManager->remove($card);
        $this->entityManager->flush();

        return $card;
    }
}
