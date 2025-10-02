<?php


namespace MintHCM\Api\Repositories;

use Doctrine\ORM\EntityRepository;

class EmployeeRolesRepository extends EntityRepository
{
    public function getCompetencies($roleId)
    {
        $dql = "SELECT c.id, c.name, c.description
                FROM MintHCM\Api\Entities\Competencies c
                JOIN MintHCM\Api\Entities\CompetencyRatings cr 
                    WITH cr.competency_id = c.id 
                        AND cr.deleted = 0 
                        AND cr.parent_id = :roleId 
                        AND cr.parent_type = 'EmployeeRoles'
                WHERE c.deleted = 0";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('roleId', $roleId);
        
        return $query->getResult();
    }

    public function getResponsibilities($roleId)
    {
        $qb = $this->createQueryBuilder('er')
            ->innerJoin('er.responsibilities', 'rr', 'WITH', 'rr.deleted = 0')
            ->where('er.id = :roleId')
            ->setParameter('roleId', $roleId)
            ->select('rr.id, rr.name, rr.description');

        return $qb->getQuery()->getResult();
    }
}