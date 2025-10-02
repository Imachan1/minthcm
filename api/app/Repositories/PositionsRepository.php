<?php


namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class PositionsRepository extends EntityRepository
{
    public function getCompetencies($positionId)
    {
        $dql = "SELECT c.id, c.name, c.description
                FROM MintHCM\Api\Entities\Competencies c
                JOIN MintHCM\Api\Entities\CompetencyRatings cr 
                    WITH cr.competency_id = c.id 
                        AND cr.deleted = 0 
                        AND cr.parent_id = :positionId 
                        AND cr.parent_type = 'Positions'
                WHERE c.deleted = 0";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('positionId', $positionId);
        
        return $query->getResult();
    }

    public function getResponsibilities($positionId)
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.responsibilities', 'r', 'WITH', 'r.deleted = 0')
            ->where('p.id = :positionId')
            ->setParameter('positionId', $positionId)
            ->select('r.id, r.name, r.description');

        return $qb->getQuery()->getResult();
    }
}