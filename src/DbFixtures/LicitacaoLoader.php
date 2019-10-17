<?php
declare(strict_types=1);

namespace App\DbFixtures;

use App\Entities\Licitacao;
use App\Entities\Municipio;
use App\Services\Transparencia;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PHPStan\Type\Php\ArrayMapFunctionReturnTypeExtension;

use function DI\string;

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
        $this->codigoIbge = $codigoIbge;
        $this->diaInicial = $diaInicio;
        $this->diaFinal = $diaFim;
        $this->anos = $anos;
    }

    // usando para teste 
    public function getAnos(){
        return $this->anos;
    }

    public function load(objectManager $manager)
    {
        $municipio = $manager->getRepository(Municipio::class)->findOneBy([
            'codigoIbge' => $this->codigoIbge
        ]);
        $diasPrimerio = $this->getDatasIniciais();
        $diasUltimos = $this->getDatasFinais();
        foreach($diasPrimerio as  $primeiro)
        {
            foreach($diasUltimos as $ultimo)
            {   
                $resultado = $this->transparencia->searchLicitacao($primeiro, $ultimo, $this->codigoSiafi,1);
                if(!$resultado)
                    continue;
                $lici = $this->instanceateLicitacao($resultado, $municipio);
                $manager->persist($lici);
            }
        }
        $manager->flush();
    }

    /**
     * Returns an array in the format ['01/01/2016', '..., '01/01/2018']
     *
     * @return array
     */

    private function getDatasIniciais( )
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
     * Returns an array in the format ['31/01/2016', '331/12/2016', ..., '31/01/2018']
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

private function instanceateLicitacao(array $resultado, $municipio){
        
        $dataReferencia = $resultado['dataReferencia'];
        $nomeOrgao = $resultado['nomeOrgao'];

        $codigoOrgao = $resultado['codigoSIAFI'];

        $dataPublicacao = $resultado['dataPublicacao'];

        
        $dataResultadoCompra = $resultado['dataResultadoCompra'];
        $objetoLicitacao = $resultado['objetoLiciatacao'];
        $numeroLicitacao = $resultado['numeroLicitacao'];
        $responsavelContato = $resultado['responsavelContato'];

        return new Licitacao($municipio, $dataReferencia, $nomeOrgao, $codigoOrgao, $dataPublicacao, 
            $dataResultadoCompra, $objetoLicitacao, $numeroLicitacao, $responsavelContato);
    }

    
}//fim classe

?>
