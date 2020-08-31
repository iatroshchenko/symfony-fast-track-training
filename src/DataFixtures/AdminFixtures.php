<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        if (!isset($_ENV['ADMIN_PASSWORD'])) {
            throw new \Exception('Admin password is not set in env');
        }

        $password = $_ENV['ADMIN_PASSWORD'];
        $admin = new Admin();
        $admin->setUsername('admin');
        $admin->setRoles([Admin::ROLE_ADMIN]);
        $admin->setPassword($this->encoder->encodePassword($admin, $password));

        // $product = new Product();
        // $manager->persist($product);

        $manager->persist($admin);
        $manager->flush();
    }
}
