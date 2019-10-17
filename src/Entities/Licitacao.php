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
 *      name="licitacao",
 *      indexes={
 *          @Index(name="municipio_idx", columns={"municipio"}),
 *          @Index(name="id_idx", columns={"id"}),
 *      }
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
    * @Column(name="municipio")
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
     * @Column(name="objeto_licitacao", length=255)
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

    public function __construct( Municipio $municipio, DateTime $dataReferencia, String $nomeOrgao,
    Integer $codigoOrgao, DateTime $dataPublicacao, DateTime $dataResultadoCompra, String $objetoLicitacao,
    String $numeroLicitacao, String $responsavelContato )
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
    
    public function jsonSerialize()
    {
        return
        [
            'id' => $this->id,
            'municipio' => $this->municipio,
            'dataReferencia' => $this->dataReferencia->format('d,m,y'),
            'nomeOrgao' => $this->nomeOrgao,
            'codigoOrgao' => $this->codigoOrgao,
            'dataPublicacao' => $this->dataPublicacao->format('d,m,y'),
            'dataResultado_compra' => $this->dataResultadoCompra->format('d,m,y'),
            'objetoLicitacao' => $this->objetoLicitacao,
            'numeroLicitacao' => $this->numeroLicitacao,
            'responsavelContato' => $this->responsavelContato
        ];
    }


}// fim classe

?>