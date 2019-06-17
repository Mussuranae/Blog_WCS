<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Création d’un utilisateur de type “auteur”
        $author = new User();
        $author->setEmail('author@monsite.com');
        $author->setRoles(['ROLE_AUTHOR']);
        $author->setPassword($this->passwordEncoder->encodePassword(
            $author,
            'authorpassword'
        ));

        $manager->persist($author);
        $this->addReference("author1", $author);

        // Création d’un utilisateur de type “auteur” 2
        $authorbis = new User();
        $authorbis->setEmail('authorbis@monsite.com');
        $authorbis->setRoles(['ROLE_AUTHOR']);
        $authorbis->setPassword($this->passwordEncoder->encodePassword(
            $authorbis,
            'authorbispassword'
        ));

        $manager->persist($authorbis);
        $this->addReference("author2", $authorbis);


        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);
        $manager->flush();
    }
}
