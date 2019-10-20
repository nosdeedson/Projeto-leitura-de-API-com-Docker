<?php

declare(strict_types=1);


namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

use JsonSerializable;

/**
 * @Table(
 *      name="bolsa_familia",
 *      indexes={
 *          @Index(name="data_referencia_idx", columns={"data_referencia"})
 *      }
 * )
 * @Entity
 */
class BolsaFamilia implements JsonSerializable
{
    /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Municipio", inversedBy="bolsaFamilia", fetch="EAGER")
     * @var Municipio
     */
    private $municipio;

    /**
     * @Column(name="data_referencia", type="date")
     */
    private $dataReferencia;

    /**
     * @Column(name="valor_total", type="float")
     */
    private $valorTotal;

    /**
     * @Column(name="quantidade_beneficiados", type="integer")
     */
    private $qtdBeneficiados;

    public function __construct(Municipio $municipio, DateTime $dataReferencia, float $valorTotal, int $qtdBeneficiados)
    {
        $this->municipio = $municipio;
        $this->dataReferencia =  $dataReferencia;
        $this->valorTotal = $valorTotal;
        $this->qtdBeneficiados = $qtdBeneficiados;
    }

    /**
     * Access private properties like public properties (in read-only mode)
     */
    public function __get($name)
    {
        return $this->$name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'municipio' => $this->municipio->nomeCidade,
            'data_referencia' => $this->dataReferencia->format('d/m/Y'),
            'valor_total' => 'R$ ' . \number_format($this->valorTotal, 2, ',', '.'),
            'quantidade_beneficiados' => $this->qtdBeneficiados,
        ];
    }
}
