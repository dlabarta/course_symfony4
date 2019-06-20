<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            'admin@avaibook.com' => ['ROLE_ADMIN'],
            'user1@avaibook.com' => ['ROLE_USER'],
            'user2@avaibook.com' => ['ROLE_USER'],
        ];

        foreach ($users as $email => $roles) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles($roles);
            $password = $this->encoder->encodePassword($user, '1234');
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
        }
    }
}