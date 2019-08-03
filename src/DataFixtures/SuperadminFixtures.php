<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SuperadminFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
        public function load(ObjectManager $manager)
        {
            
    
            $user = new User();
            $user->setUsername("kyadiop");
            $user->setRoles(['ROLE_SUPER_ADMIN']);
            $password = $this->encoder->encodePassword($user, 'kyadiop97');
            $user->setPassword($password);
            $user->setNomcomplet("Rokhaya Niakh Diop");
            $user->setMail("kyadiop@gmail.com");
            $user->setTel(77855565);
            $user->setAdresse("dakar");
    
            $manager->persist($user);
    
            $manager->flush();
        }
}
