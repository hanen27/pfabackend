<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{ private $manager;
     function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {     
        
        parent::__construct($registry, User::class);
        $this->manager = $manager;

    }

     function saveUser($name, $avatar, $email, $adresse,$solde_conges, $password, $encoder)
    {
        $newUser = new user();

        $newUser
            ->setname($name)
            ->setavatar($avatar)
            ->setEmail($email)
            ->setadresse($adresse)
            ->setSoldeConges($solde_conges)
            ->setPassword($encoder->encodePassword($newUser, $password))
            ->setRoles(['ROLE_USER']);

        $this->manager->persist($newUser);
        $this->manager->flush();
    }
    public function updateUser(User $user): User
    {
        $this->manager->persist($user);
        $this->manager->flush();
    
        return $user;
    }
    public function removeUser(User $user)
{
    $this->manager->remove($user);
    $this->manager->flush();
}
}