<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    // /**
    //  * @return Answer[] Returns an array of Answer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Answer
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public static function createApprovedCriteria(): Criteria
    {
        return Criteria::create()->where(Criteria::expr()->eq('status',Answer::STATUS_APPROVED));
    }

     /**
      * @return Answer[] Returns an array of Answer objects
      */
    public function findMostPopular(): array
    {
        return $this->createQueryBuilder('answer')
            ->addCriteria(self::createApprovedCriteria())
            ->orderBy('answer.votes', 'DESC')
            ->innerJoin('answer.question', 'question')
            ->addSelect('question')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Answer[] Returns an array of Answer objects
     */
    public function findPopularAnswersWithSearch(?string $search = null): array
    {
        $queryBuilder = $this->createQueryBuilder('answer')
            ->addCriteria(self::createApprovedCriteria())
            ->innerJoin('answer.question', 'question')
            ->addSelect('question')
            ->orderBy('answer.votes', 'DESC');

        if ($search) {
            $queryBuilder->andWhere(
                'answer.content LIKE :searchTerm OR question.question LIKE :searchTerm'
            )
                ->setParameter('searchTerm', '%'.$search.'%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

}
