<?php

namespace App\Repository;

use App\Entity\Deck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Deck|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deck|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deck[]    findAll()
 * @method Deck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeckRepository extends ServiceEntityRepository implements DeckRepositoryInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager|\Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * DeckRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deck::class);
        $this->entityManager = $this->getEntityManager();
    }

    /**
     * @param Deck $deck
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Deck $deck)
    {
        $this->entityManager->persist($deck);
        $this->entityManager->flush();

        return $deck;
    }

    /**
     * @param Deck $deck
     * @return Deck
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Deck $deck)
    {
        $this->entityManager->remove($deck);
        $this->entityManager->flush();

        return $deck;
    }
}
