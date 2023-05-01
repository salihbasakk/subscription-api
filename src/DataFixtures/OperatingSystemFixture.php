<?php

namespace App\DataFixtures;

use App\Entity\OperatingSystem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OperatingSystemFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as $data) {
            $os = new OperatingSystem();

            $os->setName($data['name']);

            $manager->persist($os);
        }

        $manager->flush();
    }

    private function getData(): array
    {
        return [
            ['name' => 'ios'],
            ['name' => 'google']
        ];
    }
}
