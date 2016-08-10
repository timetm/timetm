<?php

/**
 * This file is part of the TimeTM package.
 *
 * (c) TimeTM <https://github.com/timetm>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TimeTM\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository {

    public function findAllByUser($user) {

        return $this->createQueryBuilder('e')
            ->leftjoin('e.agenda', 'a')
            ->leftjoin('a.user', 'u')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
