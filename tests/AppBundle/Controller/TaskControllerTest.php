<?php

namespace Tests\AppBundle\Controller;

use App\Tests\AppBundle\BaseTest;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskControllerTest extends BaseTest
{
    /**
     * @runInSeparateProcess
     */
    public function testIndex()
    {

		$crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    }
	/**
	 * Test fonctionnel permettant de créer une tâche
	 * @runInSeparateProcess
	 */
	public function testCreateANewTask(){

		$this->client->request('GET', '/');
		$this->client->followRedirect();
		$this->client->submitForm('Se connecter', [
			'_username' => 'user_with_role_user',
			'_password' => '1234'
		]);
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$this->client->clickLink('Créer une nouvelle tâche');
		$this->client->submitForm('Ajouter', [
			'task[title]' => "Ma super tâche",
			'task[content]' => "Ma super tâche contient du texte."
		]);

		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$this->assertSelectorExists('div:contains("Superbe ! La tâche a été bien été ajoutée.")');

	}

	/**
	 * Test fonctionnel permettant de créer une tâche et supprimer sa propre tâche
	 * @runInSeparateProcess
	 */
	public function testDeleteOwnTask(){

		/** @var EntityManagerInterface $entityManager */
		$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

		$this->client->request('GET', '/');
		$this->client->followRedirect();
		$this->client->submitForm('Se connecter', [
			'_username' => 'user_with_role_user',
			'_password' => '1234'
		]);
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$user = $entityManager->getRepository(User::class)->findBy(['username' => 'user_with_role_user']);
		$nbTasksBeforeDelete = count($entityManager->getRepository(Task::class)->findBy(['user' => $user]));

		$this->client->request('GET', '/tasks');

		$this->client->submitForm('Supprimer');

		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();
		$nbTasksAfterDelete = count($entityManager->getRepository(Task::class)->findBy(['user' => $user]));


		$this->assertNotEquals($nbTasksBeforeDelete, $nbTasksAfterDelete);

		$this->assertSelectorExists('div:contains("La tâche a bien été supprimée.")');

	}

	/**
	 * Test fonctionnel permettant de créer une tâche et marquer la tâche comme faite
	 * @runInSeparateProcess
	 */
	public function testToggleTaskIsDone(){

		$this->client->request('GET', '/');
		$this->client->followRedirect();
		$this->client->submitForm('Se connecter', [
			'_username' => 'user_with_role_user',
			'_password' => '1234'
		]);
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$this->client->request('GET', '/tasks');
		$this->assertResponseIsSuccessful();

		$this->client->submitForm('Marquer comme faite');


		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$this->assertSelectorExists('div:contains("Superbe ! La tâche Task_#0 a bien été marquée comme faite.")');

	}





}
