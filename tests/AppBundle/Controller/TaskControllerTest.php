<?php

namespace Tests\AppBundle\Controller;

use App\Tests\AppBundle\BaseTest;
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





}
