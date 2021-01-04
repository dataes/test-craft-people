<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @var \Doctrine\ORM\EntityManager|\Doctrine\ORM\EntityManagerInterface
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Card $card)
    {
        $this->entityManager->remove($card);
        $this->entityManager->flush();

        return $card;
    }
}
