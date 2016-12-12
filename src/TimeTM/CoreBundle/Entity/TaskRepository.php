<?php

namespace TimeTM\CoreBundle\Entity;

/**
 * TaskRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TaskRepository extends \Doctrine\ORM\EntityRepository {


    /**
     *  Find all active tasks (without parameter)
     *  Find all active tasks for an user
     *
     *  @return queryBuilder
     */
    public function findAllActive($user = NULL) {

        $qb = $this->createQueryBuilder('t')
            ->leftjoin('t.userassigned', 'u')
            ->where('t.donedate is NULL');

        if ($user) {
            $qb->andWhere('t.userassigned = :user or t.userassigned is NULL')
            ->setParameter('user', $user);
        }

        return $qb;
    }


    /**
     *  Find active tasks in next n days
     *
     *  @return queryBuilder
     */
    public function findActiveInNextDays($days) {

        return $this->createQueryBuilder('t')
            ->where('DATE_DIFF(t.duedate, CURRENT_DATE()) < :days')
            ->andWhere('t.donedate is NULL')
            ->setParameter('days', $days)
            ->getQuery()
            ->getResult();
    }

}
