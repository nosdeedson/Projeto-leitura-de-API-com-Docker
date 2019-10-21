<?php
declare(strict_types=1);

namespace App\DbFixtures;

use App\Entities\Licitacao;
use App\Entities\Municipio;
use App\Services\Transparencia;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use function DI\string;
use function GuzzleHttp\json_decode;

class LicitacaoLoader implements FixtureInterface
{
    private $transparencia;
    private $diaInicial;
    private $diaFinal;
    private $anos;
    private $codigoSiafi;
    private $codigoIbge;

    public function __construct( Transparencia $t, $codSiafi, array $anos, $diaInicio, $diaFim, 
            $codigoIbge) 
    {
        $this->transparencia = $t;
        $this->codigoSiafi = $codSiafi;
        $this->anos = $anos;
        $this->diaInicial = $diaInicio;
        $this->diaFinal = $diaFim;
        $this->codigoIbge = $codigoIbge;
    }

    public function load(objectManager $manager)
    {
        $municipio = $manager->getRepository(Municipio::class)->findOneBy([
            'codigoIbge' => $this->codigoIbge]);
            $diasPrimerio = $this->getDatasIniciais();
            $diasUltimos = $this->getDatasFinais();
            
        for( $i = 0; $i < sizeof($diasPrimerio); $i++){
            $resultado =$this->transparencia->searchLicitacao($diasPrimerio[$i], $diasUltimos[$i], $this->codigoSiafi,'1');
            if( !$resultado)
            {
                //print_r("pesquisa retornou nulo!!");
                continue;
            }
            $lici = $this->instanceateLicitacao($resultado[0], $municipio);
            if( !$lici)
            {
                //print_r("objeto não foi instanciado!!");
                continue;
            }
            //print_r($lici->getMunicipio()->getName()."\n". $lici->getMunicipio()->getCodigoIbge()."\n");
            // ao executar algumas vezes ocorre erro 
            // no terminal diz que chamando objeto Municipio nullo
            // o instaciando com municipio nulo
            //var_dump($lici); 
            $manager->persist($lici); 
           
        }
        $manager->flush(); 
    }

    /**
     * Returns an array in the format ['01/01/2016', '..., '01/01/2018']
     *
     * @return array
     */

    private function getDatasIniciais()
    {
        $anoMesArrayResult = [];
        $anoMesArray = [];
        foreach ($this->anos as $ano) {
            $anoMesArrayResult = [];
            foreach ($this->anos as $ano) {
                $anoMesArray = array_map(function ($mes) use ($ano)
                {
                    return sprintf('%s%s%s', $this->diaInicial.'/', str_pad((string) $mes, 2, '0', STR_PAD_LEFT), '/'.$ano);
                }, range(1, 12, 1));
    
                $anoMesArrayResult = array_merge($anoMesArrayResult, $anoMesArray);
            }
    
            return $anoMesArrayResult;
        }
    }
    /**
     * Returns an array in the format ['31/01/2016', '31/12/2016', ..., '31/01/2018']
     *
     * @return array
     */
    private function getDatasFinais()
    {
        $anoMesArrayResult = [];
        $anoMesArray = [];
        foreach ($this->anos as $ano) {
            $anoMesArrayResult = [];
            foreach ($this->anos as $ano) {
                $anoMesArray = array_map(function ($mes) use ($ano)
                {   
                    return sprintf('%s%s%s', $this->diaFinal.'/', str_pad((string) $mes, 2, '0', STR_PAD_LEFT), '/'.$ano);
                }, range(1, 12, 1));
    
                $anoMesArrayResult = array_merge($anoMesArrayResult, $anoMesArray);
            }
    
            return $anoMesArrayResult;
        }
    }

    private function instanceateLicitacao(array $resultado, Municipio $municipio)
    {
        
        //foreach( $resultado as $r){
            $dataResultadoCompra = DateTime::createFromFormat('d/m/Y', $resultado['dataResultadoCompra']);
            $dataReferencia = DateTime::createFromFormat('d/m/Y', $resultado['dataReferencia']);
    
            $dataPublicacao = DateTime::createFromFormat('d/m/Y', $resultado['dataPublicacao']);
    
            // particiona o JSon em objetos menores até o último para atribuir o valor
            $teste = $resultado['unidadeGestora'];
            $t = $teste['orgaoVinculado'];
            $c = (int) $t['codigoSIAFI'];
            $n = $t['nome'];
            //fim
            $codigoOrgao = (int) $c;
            $nomeOrgao = $n;
    
            // particiona o JSon em objetos menores até o último para atribuir o valor
            $teste = $resultado['licitacao'];   
            // fim
            $objetoLicitacao = $teste['objeto'];
            $numeroLicitacao = $teste['numeroProcesso'];
            $responsavelContato = $teste['contatoResponsavel']; 
            $li = new Licitacao($municipio, $dataReferencia, $nomeOrgao, $codigoOrgao, $dataPublicacao, 
                $dataResultadoCompra, $objetoLicitacao, $numeroLicitacao, $responsavelContato);
            
            // objeto licitaçao instanciado corretamente municipio não nulo
            return new Licitacao($municipio, $dataReferencia, $nomeOrgao, $codigoOrgao, $dataPublicacao, 
               $dataResultadoCompra, $objetoLicitacao, $numeroLicitacao, $responsavelContato);
       // }
 
    }

    
}//fim classe

?>
