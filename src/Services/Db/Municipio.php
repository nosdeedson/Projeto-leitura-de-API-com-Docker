<?php

namespace App\Services\Db;

use App\Entities\Municipio as MunicipioEntity;
use Doctrine\ORM\EntityManager;

class Municipio
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function findAll()
    {
        return $this->em->getRepository(MunicipioEntity::class)->findAll();
    }
}