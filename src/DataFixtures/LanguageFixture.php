<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as $data) {
            $language = new Language();

            $language->setName($data['name']);
            $language->setCode($data['code']);

            $manager->persist($language);
        }

        $manager->flush();
    }

    private function getData(): array
    {
        return [
            [
                'name' => 'Turkish',
                'code' => 'tr'
            ],
            [
                'name' => 'English',
                'code' => 'en'
            ],
        ];
    }
}
