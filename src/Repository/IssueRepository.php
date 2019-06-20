<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Issue;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Issue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Issue::class);
    }


    public function findByCategoryAndTitle($user, $category, $title)
    {
        $qb = $this->createQueryBuilder('issue')
            ->orderBy('issue.id', 'ASC')
        ;
        if (!is_null($user)){
            $qb->where('issue.user = :user')
                ->setParameter('user', $user);
        }
        if (!empty($category)) {
            $qb->andWhere('issue.category = :category')
                ->setParameter('category', $category);
        }
        if (!empty($title)) {
            $qb->andWhere('issue.title like :title')
                ->setParameter('title', '%'.$title.'%');
        }

        return $qb->getQuery()->getResult();
    }

    public function getLastByYear($year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $result = $this->createQueryBuilder('issue')
            ->select('issue')
            ->where('YEAR(issue.createdAt) = :year')
            ->setParameter('year', $year)
            ->orderBy('issue.number', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $result ? $result[0] : null;
    }
}
