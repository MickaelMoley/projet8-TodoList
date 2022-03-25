<?php

namespace Tests\AppBundle\Controller;

use App\Tests\AppBundle\BaseTest;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends BaseTest
{

	/**
	 * Test si un utilisateur peut se connecter
	 * @runInSeparateProcess
	 */
	public function testUserCanConnect(){

		$this->client->request('GET', '/');
		$this->client->followRedirect();
		$this->client->submitForm('Se connecter', [
			'_username' => 'user_with_role_user',
			'_password' => '1234'
		]);
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$this->assertSelectorExists('a:contains("Se déconnecter")');
	}

	/**
	 * Test si un utilisateur peut se connecter et se déconnecter
	 * @runInSeparateProcess
	 */
	public function testUserCanConnectAndDisconnect(){

		$this->client->request('GET', '/');
		$this->client->followRedirect();
		$this->client->submitForm('Se connecter', [
			'_username' => 'user_with_role_user',
			'_password' => '1234'
		]);
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

		$this->assertSelectorExists('a:contains("Se déconnecter")');
		$this->client->clickLink('Se déconnecter');

		$this->client->followRedirect();
		$this->client->followRedirect();
		$this->assertResponseIsSuccessful();

	}

	/**
	 * Test si un utilisateur peut se créer un compte
	 * @runInSeparateProcess
	 */
	public function testUserCanCreateAnAccount(){

		$this->client->request('GET', '/');
		$this->client->followRedirect();

		$usersCount = count($this->manager->getRepository(User::class)->findAll());

		$this->client->clickLink('Créer un utilisateur');
		$this->assertResponseIsSuccessful();

		$this->client->submitForm('Ajouter', [
			'user[username]' 			=> 'user_register',
			'user[password][first]' 	=> '1234',
			'user[password][second]' 	=> '1234',
			'user[email]'				=> 'user_register@mail.test'
		]);

		$this->client->followRedirect();
		$this->client->followRedirect();
		$this->client->followRedirect();

		$this->assertResponseIsSuccessful();

		$this->assertSelectorExists('div:contains("Superbe ! L\'utilisateur a bien été ajouté.")');
		$newUsersCount = count($this->manager->getRepository(User::class)->findAll());

		$this->assertNotEquals($usersCount, $newUsersCount);
		

	}

    /**
     * Fonction permettant de se connecter en tant qu'admin
     * @runInSeparateProcess
     */
    public function testIfUserAdminCanConnect(){

        $this->client->request('GET', '/');
        $this->client->followRedirect();
        $this->client->submitForm('Se connecter', [
            '_username' => 'user_with_role_admin',
            '_password' => '1234'
        ]);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

    }

    /**
     * Fonction permettant de se connecter en tant qu'admin et de lister les utilisateurs
     * @runInSeparateProcess
     */
    public function testIfUserAdminCanListAllUsers(){


        $this->client->request('GET', '/');
        $this->client->followRedirect();
        $this->client->submitForm('Se connecter', [
            '_username' => 'user_with_role_admin',
            '_password' => '1234'
        ]);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        //Lister les utilisateurs depuis la page des utilisateurs
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();

    }

    /**
     * Fonction permettant de se connecter en tant qu'admin et de modifier un utilisateur
     * @runInSeparateProcess
     */
    public function testIfUserAdminCanEditUser(){

        //On se connecte en tant qu'admin
        $this->client->request('GET', '/');
        $this->client->followRedirect();
        $this->client->submitForm('Se connecter', [
            '_username' => 'user_with_role_admin',
            '_password' => '1234'
        ]);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        //On modifie un utilisateur
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->getCrawler();
        //Sélectionne la dernière tâche qui correspond à une tâche reliée à l'utilisateur Anonymous
        $taskNode = $crawler->filter('html > body > .container > .row')->last();
        $taskNode = $taskNode->filter('div table');
        $userNode = $taskNode->filter('tbody tr:nth-child(1)');
        $linkUserEdit = $userNode->filter('a')->link();

        $this->client->click($linkUserEdit);
        $this->assertResponseIsSuccessful();

        //On modifie l'utilisateur
        $this->client->submitForm('Modifier', [
            'user[username]' 			=> 'user_with_role_user',
            'user[password][first]' 	=> '5678',
            'user[password][second]' 	=> '5678',
            'user[email]'				=> 'user_with_role_user_modified@mail.com'
        ]);

        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('.alert.alert-success');

    }

}
