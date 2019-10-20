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
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;

/**
 * @Table(
 *      name="licitacao"
 * )
 * @Entity
 */

class Licitacao implements JsonSerializable
{
    /**
     * @Id
     * @Column(name="id", type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Municipio", inversedBy="licitacoes", fetch="EAGER")
     * @var Municipio
     */
    private $municipio;

    /**
    * @Column(name="data_referencia", type="date")
    */
    private $dataReferencia;

    /**
     * @Column(name="nome_orgao")
     */
    private $nomeOrgao;

    /**
     * @Column(name="codigo_orgao", type="integer")
     */
    private $codigoOrgao;

    /**
     * @Column(name="data_publicacao", type="date")
     */
    private $dataPublicacao;

    /**
     * @Column(name="data_resultado_compra", type="date")
     */
    private $dataResultadoCompra;

    /**
     * @Column(name="objeto_licitacao", length=1500)
     */
    private $objetoLicitacao;

    /**
     * @Column(name="numero_licitacao")
     */
    private $numeroLicitacao;

    /**
     * @Column(name="responsavel_contato")
     */
    private $responsavelContato;
    public function __construct( Municipio $municipio, DateTime $dataReferencia, string $nomeOrgao,
    int $codigoOrgao, DateTime $dataPublicacao, DateTime $dataResultadoCompra, string $objetoLicitacao,
    string $numeroLicitacao, string $responsavelContato )
    {
        $this->municipio= $municipio;
        $this->dataReferencia= $dataReferencia;
        $this->nomeOrgao= $nomeOrgao;
        $this->codigoOrgao= $codigoOrgao;
        $this->dataPublicacao = $dataPublicacao;
        $this->dataResultadoCompra = $dataResultadoCompra;
        $this->objetoLicitacao = $objetoLicitacao;
        $this->numeroLicitacao = $numeroLicitacao;
        $this->responsavelContato =$responsavelContato;
    }
    public function getMunicipio()
    {
        return $this->municipio;
    }
    
    public function jsonSerialize()
    {
        return
        [ 
            'municipio' => $this->municipio,
            'data_referencia' => $this->dataReferencia->format('d/mY'), 
            'nome_orgao' => $this->nomeOrgao,
            'codigo_orgao' => $this->codigoOrgao,
            'data_publicacao' => $this->dataPublicacao->format('d/mY'),
            'data_resultado_compra' =>  $this->dataResultadoCompra->format('d/mY'),
            'objeto_licitacao' => $this->objetoLicitacao,
            'numero_licitacao' => $this->numeroLicitacao,
            'responsavel_contato' => $this->responsavelContato
        ];
    }
 

}// fim classe

?>