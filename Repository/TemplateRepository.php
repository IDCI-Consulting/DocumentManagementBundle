<?php

namespace IDCI\Bundle\DocumentManagementBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class TemplateRepository extends EntityRepository
{
    /**
     * Find template by id or slug.
     *
     * @param string $id
     *
     * @return Template|null
     */
    public function findByIdOrSlug($id)
    {
        if (Uuid::isValid($id)) {
            return $this->find($id);
        }

        return $this->findOneBy(array('slug' => $id));
    }
}
