<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Match;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * MatchRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MatchRepository extends EntityRepository
{
    /**
     * Return array of incoming item matches for given user
     *
     * @param User $user
     * @param int $status
     *
     * @return array
     */
    public function findMatchesByUser(User $user, int $status = Match::STATUS_ACCEPTED): array
    {
        $matches = $this->createQueryBuilder('m')
            ->leftJoin('m.itemRespondent', 'i')
            ->where('i.user = :user')
            ->andWhere('m.status = :status')
            ->setParameters([
                'user' => $user,
                'status' => $status,
            ])
            ->getQuery()
            ->getResult();

        return $matches;
    }
}
