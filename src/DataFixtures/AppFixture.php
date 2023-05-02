<?php

namespace App\DataFixtures;

use App\Entity\App;
use App\Repository\OperatingSystemRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixture extends Fixture implements DependentFixtureInterface
{
    private OperatingSystemRepository $operatingSystemRepository;

    public function __construct(OperatingSystemRepository $operatingSystemRepository)
    {
        $this->operatingSystemRepository = $operatingSystemRepository;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as $data) {
            $app = new App();

            $app->setName($data['name']);
            $app->setUsername($data['username']);
            $app->setPassword($data['password']);
            $app->setOs($this->operatingSystemRepository->findOneBy(['name' => $data['osName']]));

            $manager->persist($app);
        }

        $manager->flush();
    }

    private function getData(): array
    {
        return [
            [
                'name' => 'App1',
                'username' => 'username1',
                'password' => 'password1',
                'osName' => 'ios'
            ],
            [
                'name' => 'App1',
                'username' => 'username2',
                'password' => 'password2',
                'osName' => 'google'
            ],
            [
                'name' => 'App2',
                'username' => 'username3',
                'password' => 'password3',
                'osName' => 'ios'
            ],
            [
                'name' => 'App2',
                'username' => 'username4',
                'password' => 'password4',
                'osName' => 'google'
            ],
            [
                'name' => 'App3',
                'username' => 'username5',
                'password' => 'password5',
                'osName' => 'ios'
            ],
            [
                'name' => 'App3',
                'username' => 'username5',
                'password' => 'password5',
                'osName' => 'google'
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            OperatingSystemFixture::class,
        ];
    }
}
