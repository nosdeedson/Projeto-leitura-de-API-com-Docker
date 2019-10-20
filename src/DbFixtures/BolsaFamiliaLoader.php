<?php

declare(strict_types=1);

namespace App\DbFixtures;

use App\Entities\BolsaFamilia;
use App\Entities\Municipio;
use App\Services\Transparencia;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BolsaFamiliaLoader implements FixtureInterface
{
    private $tranparencia;
    private $anos;
    private $codigosIbge;

    public function __construct(Transparencia $tr, array $anos, array $codigosIbge)
    {
        $this->tranparencia = $tr;
        $this->anos = $anos;
        $this->codigosIbge = $codigosIbge;
    }

    public function load(ObjectManager $manager)
    {
        $anoMesArray = $this->getAnoMes();

        foreach ($this->codigosIbge as $codigoIbge) {
            $municipio = $manager->getRepository(Municipio::class)->findOneBy([
                'codigoIbge' => $codigoIbge
            ]);

            if (!$municipio) {
                continue;
            }

            foreach ($anoMesArray as $anoMes) {
                $result = $this->tranparencia->searchBolsaFamilia($anoMes, (string) $codigoIbge, 1);
                
                if (!$result) {
                    continue;
                }

                $bf = $this->instanciateBolsaFamilia($municipio, $result[0]);
                //var_dump($bf);
                $manager->persist($bf);
            }
        }

        $manager->flush();
    }

    /**
     * Returns an array in the format ['201601', '201602', ..., '201703', '201704', ..., '201812']
     *
     * @return array
     */
    private function getAnoMes()
    {
        $anoMesArrayResult = [];
        foreach ($this->anos as $ano) {
            $anoMesArray = array_map(function ($mes) use ($ano) {
                return sprintf('%s%s', $ano, str_pad((string) $mes, 2, '0', STR_PAD_LEFT));
            }, range(1, 12, 1));

            $anoMesArrayResult = array_merge($anoMesArrayResult, $anoMesArray);
        }

        return $anoMesArrayResult;
    }

    private function instanciateBolsaFamilia(Municipio $m, array $data)
    {
        //var_dump($data);
        $dataReferencia = DateTime::createFromFormat('d/m/Y', $data['dataReferencia']);
        if (!$dataReferencia) {
            throw new \Exception('Erro ao tentar criar a data de referência ' . $data['dataReferencia']);
        }
        $valorTotal = (float) $data['valor'];
        $qtdBeneficiados = (int) $data['quantidadeBeneficiados'];

        return new BolsaFamilia($m, $dataReferencia, $valorTotal, $qtdBeneficiados);
        
    }
}
