<?php

namespace App\Tests\AppBundle\Controller;

use App\Tests\AppBundle\BaseTest;

class SecurityControllerTest extends BaseTest
{
    /**
     * @runInSeparateProcess
     */
    public function testIfUserCanLogout(){


        $this->client->request('GET', '/');
        $this->client->followRedirect();
        $this->client->submitForm('Se connecter', [
            '_username' => 'user_with_role_user',
            '_password' => '1234'
        ]);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();


        $this->client->request('GET', '/logout');

        $this->client->followRedirect();
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertEquals('/login', $this->client->getRequest()->getPathInfo());
    }
}