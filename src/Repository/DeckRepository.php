<?php

namespace App\Repository;

use App\Entity\Deck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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
     * @var EntityManager|EntityManagerInterface
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
     * @return Deck
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Deck $deck): Deck
    {
        $this->entityManager->persist($deck);
        $this->entityManager->flush();

        return $deck;
    }

    /**
     * @param Deck $deck
     * @return Deck
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Deck $deck): Deck
    {
        $this->entityManager->remove($deck);
        $this->entityManager->flush();

        return $deck;
    }
}
