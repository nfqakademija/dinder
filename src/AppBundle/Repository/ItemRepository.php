<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Item;
use AppBundle\Entity\Match;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * ItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends EntityRepository
{
    /**
     * Returns array of items that matches these conditions:
     * 1. Item owner is in same location as current user
     * 2. Item is available for trade (status = active)
     * 3. Item owner is not current user
     * 4. Item is in tradable item's categories list
     * 5. Item value matches tradable item's value with margin
     * 6. Item is not rejected by tradable item
     * 7. Item is not in the list of tradable item proposed match offers
     * 8. Item is not in the list of tradable item received match offers
     *
     * @param Item $item
     * @param User $user
     * @param int $margin
     * @param int $limit
     *
     * @return array
     */

    public function findAvailableMatches(Item $item, User $user, int $margin, int $limit): array
    {
        $subquery = $this->createQueryBuilder('s')
            ->select('s.id')
            ->leftJoin('s.matchesResponseItem', 'sm')
            ->where('sm.status = :status_accepted OR sm.status = :status_rejected')
            ->andWhere('sm.itemOwner = :id')
            ->setParameters([
                'status_accepted' => Match::STATUS_ACCEPTED,
                'status_rejected' => Match::STATUS_REJECTED,
                'id' => $item->getId(),
            ])
            ->getQuery()->getArrayResult();

        $exclude = array_map(function ($match) {
            return $match['id'];
        }, $subquery);

        $exclude[] = $item->getId();

        $items = $this
            ->createQueryBuilder('i')
            ->leftJoin('i.user', 'iu')
            ->where('iu.location = :location')
            ->andWhere('i.status = :status_active')
            ->andWhere('iu.id != :id')
            ->andWhere('i.category IN (:categories)')
            ->andWhere('i.value >= :min_value')
            ->andWhere('i.value <= :max_value')
            ->andWhere('i.id NOT IN (:exclude)')
            ->setParameters([
                'location' => $user->getLocation(),
                'status_active' => Item::STATUS_ACTIVE,
                'id' => $user->getId(),
                'min_value' => $item->getValue() - $margin,
                'max_value' => $item->getValue() + $margin,
                'categories' => $item->getCategoriesToMatchArray(),
                'exclude' => $exclude,
            ])
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $items;
    }

    /**
     * Returns array the most popular items
     *
     * @param int $limit
     *
     * @return array
     */
    public function findFeatured(int $limit): array
    {
        return $this
            ->createQueryBuilder('i')
            ->orderBy('i.approvals')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
