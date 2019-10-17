<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\OneToMany;

use JsonSerializable;

/**
 * @Table(
 *      name="municipio",
 *      indexes={
 *          @Index(name="codigo_ibge_idx", columns={"codigo_ibge"}),
 *          @Index(name="nome_cidade_idx", columns={"nome_cidade"})
 *      }
 * )
 * @Entity
 * @property-read int $id
 * @property-read string $codigoIbge
 * @property-read string $nomeCidade
 */
class Municipio implements JsonSerializable
{
    /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     *
     * Codes available in https://cidades.ibge.gov.br/.
     *
     * @Column(name="codigo_ibge", type="string", length=10)
     */
    private $codigoIbge;

    /**
     * @Column(name="nome_cidade", type="string", length=255)
     */
    private $nomeCidade;

    /**
     * @OneToMany(targetEntity="BolsaFamilia", mappedBy="municipio")
     */
    private $bolsaFamilia;

    /**
     * @OneToMany(targetEntity="Licitacao", mappedBy="municipio")
     */
    private $licitacoes;

    public function __construct(int $codigoIbge, string $nomeCidade)
    {
        $this->codigoIbge = $codigoIbge;
        $this->nomeCidade = $nomeCidade;
    }

    /**
     * Access private properties like public properties (in read-only mode)
     *
     * Ex: $municipio->codigoIbg
     */
    public function __get($name)
    {
        return $this->$name;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'codigo_ibge' => $this->codigoIbge,
            'nome_cidade' => $this->nomeCidade,
        ];
    }
}
