<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadAppFixtures implements FixtureInterface {


	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}

	/**
	 * @param ObjectManager $manager
	 * @return mixed
	 */
	public function load(ObjectManager $manager)
	{
		$this->createGuestUser($manager);
		$this->createAdminUser($manager);
		$this->createAnonymousUser($manager);
	}

	public function createGuestUser(ObjectManager $manager){

		$user = new User();
		$user->setEmail('user_with_role_user@mail.test');
		$user->setUsername('user_with_role_user');
		$user->setPassword($this->passwordHasher->hashPassword($user, '1234'));
		$user->setRoles(['ROLE_USER']);

		$manager->persist($user);
		$manager->flush();
	}

	private function createAdminUser(ObjectManager $manager)
	{
		$user = new User();
		$user->setEmail('user_with_role_admin@mail.test');
		$user->setUsername('user_with_role_admin');
		$user->setPassword($this->passwordHasher->hashPassword($user, '1234'));
		$user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

		$manager->persist($user);
		$manager->flush();
	}

	private function createAnonymousUser(ObjectManager $manager)
	{
		$user = new User();
		$user->setEmail('anonymous_user@mail.test');
		$user->setUsername('anonymous_user');
		$user->setPassword($this->passwordHasher->hashPassword($user, '1234'));
		$user->setRoles(['ROLE_USER']);

		$manager->persist($user);
		$manager->flush();
	}
}