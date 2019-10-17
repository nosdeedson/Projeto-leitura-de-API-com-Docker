<?php

declare(strict_types=1);

namespace App\DbFixtures;

use App\Entities\Municipio;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MunicipioLoader implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $itajuba = new Municipio(3132404, 'Itajubá');
        $pousoAlegre = new Municipio(3152501, 'Pouso Alegre');

        $manager->persist($itajuba);
        $manager->persist($pousoAlegre);

        $manager->flush();
    }
}