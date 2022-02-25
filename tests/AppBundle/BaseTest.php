<?php

namespace App\Tests\AppBundle;

use AppBundle\DataFixtures\ORM\LoadAppFixtures;
use AppBundle\DataFixtures\TestFixtures;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseTest extends WebTestCase
{
	protected ManagerRegistry $manager;
	protected ?KernelBrowser $client = null;


	public function setUp(): void
	{
		parent::setUp();
		static::$kernel = static::createKernel();
		static::$kernel->boot();

		$this->manager = static::$kernel->getContainer()
			->get('doctrine');

		$this->client = static::createClient();

		$this->loadFixtures();

	}

	public function tearDown(): void
	{

		$users = $this->manager->getRepository(User::class)->findAll();
		$tasks = $this->manager->getRepository(Task::class)->findAll();

		foreach ($users as $user){
			$this->manager->getManager()->remove($user);
		}

		foreach ($tasks as $task)
		{
			$this->manager->getManager()->remove($task);
		}

		$this->manager->getManager()->flush();
	}


	private function loadFixtures(){

		$fixtures = static::getContainer()->get('AppBundle\DataFixtures\ORM\LoadAppFixtures');
		$fixtures->load($this->manager->getManager());
	}

	static function getContainer() : ContainerInterface
	{
		return static::$kernel->getContainer();
	}

	/**
	 * Vérifie que le site est actif et qu'il redirige bien vers la page login
	 * @runInSeparateProcess
	 */
	public function testIfProjectIsAlive(){

		$this->client->request('GET', '/');

		$this->assertResponseRedirects();
	}

}