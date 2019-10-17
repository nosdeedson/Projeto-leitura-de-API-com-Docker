<?php

namespace App\Services\Db;

use App\Entities\Licitacao as LicitacaoEntity;
use Doctrine\ORM\EntityManager;

class Licitacao
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Busca informações de liciatção de um orgão  em um intervalo de datas.
     *
     * Para mais informações sobre o queryBuilder consulte:
     * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html#the-querybuilder
     *
     * @param \DateTimeInterface $dataInicial
     * @param \DateTimeInterface $dataFinal
     * @param string $codigoOrgao
     * @return array
     */
    public function findBetweenDates(\DateTimeInterface $dataInicial, \DateTimeInterface $dataFinal, string $codigoIbge)
    {
        $qb = $this->em->createQueryBuilder();

        $results = $qb
            ->select('b')
            ->from(LicitacaoEntity::class, 'b')
            ->join('b.municipio', 'm')
            ->add('where', 'm.codigoIbge = :codigoIbge and b.dataReferencia between :startReferenceDate and :endReferenceDate')
            ->setParameters([
                'codigoIbge' => $codigoIbge,
                'startReferenceDate' => $dataInicial,
                'endReferenceDate' => $dataFinal,
            ])
            ->getQuery()
            ->getResult();

        return $results;
    }
}
