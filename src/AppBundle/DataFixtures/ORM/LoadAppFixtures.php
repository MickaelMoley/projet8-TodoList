<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadAppFixtures implements FixtureInterface {


	private UserPasswordHasherInterface $passwordHasher;
	private ObjectManager $manager;


	const NB_TASKS_PER_USERS = 10;

	public $guestUser = null;
	public $adminUser = null;
	public $anonymousUser = null;


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
		$this->manager = $manager;
		$this->createGuestUser($manager);
		$this->createAdminUser($manager);
		$this->createAnonymousUser($manager);
		$this->createTasks();
	}

	public function createGuestUser(ObjectManager $manager){

		$user = new User();
		$user->setEmail('user_with_role_user@mail.test');
		$user->setUsername('user_with_role_user');
		$user->setPassword($this->passwordHasher->hashPassword($user, '1234'));
		$user->setRoles(['ROLE_USER']);

		$manager->persist($user);
		$manager->flush();
		$this->guestUser = $user;
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
		$this->adminUser = $user;
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
		$this->anonymousUser = $user;
	}

	private function createTasks()
	{
		
		$this->createTasksForGuestUser();
		$this->createTasksForAdminUser();
		$this->createTasksForAnonymousUser();

	}

	/**
	 * Créer des tâches pour l'utilisateur simple
	 * @return void
	 */
	private function createTasksForGuestUser()
	{

		for ($i = 0; $i < self::NB_TASKS_PER_USERS; $i++){

			$task = new Task();
			$task->setTitle(sprintf("Task_#%s", $i));
			$task->setContent(sprintf('Super contenu'));
			$task->setCreatedAt(new \DateTime());
			$task->setUser($this->guestUser);
			$this->manager->persist($task);
		}

		$this->manager->flush();
	}

	/**
	 * Créer des tâches pour l'utilisateur Admin
	 * @return void
	 */
	private function createTasksForAdminUser()
	{
		for ($i = 0; $i < self::NB_TASKS_PER_USERS; $i++){

			$task = new Task();
			$task->setTitle(sprintf("Task_#%s", $i));
			$task->setContent(sprintf('Super contenu'));
			$task->setCreatedAt(new \DateTime());
			$task->setUser($this->adminUser);
			$this->manager->persist($task);
		}

		$this->manager->flush();
	}

	/**
	 * Créer des tâches pour l'utilisateur anonyme
	 * @return void
	 */
	private function createTasksForAnonymousUser()
	{
		for ($i = 0; $i < self::NB_TASKS_PER_USERS; $i++){

			$task = new Task();
			$task->setTitle(sprintf("Task_#%s", $i));
			$task->setContent(sprintf('Super contenu'));
			$task->setCreatedAt(new \DateTime());
			$task->setUser($this->anonymousUser);
			$this->manager->persist($task);
		}

		$this->manager->flush();
	}
}