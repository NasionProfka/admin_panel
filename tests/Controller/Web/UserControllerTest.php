<?php

namespace App\Tests\Controller\Web;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private string $baseUrl;
    private Group $group;
    private User $user;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->baseUrl = 'http://localhost:40215';
        $this->client = static::createClient();

        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        $groupRepository = $entityManager->getRepository(Group::class);
        $groupEntities = $groupRepository->findBy([], [], $limit = 1);
        $this->group = $groupEntities[0];

        $userRepository = $entityManager->getRepository(User::class);
        $userEntities = $userRepository->findBy([], [], $limit = 1);
        $this->user = $userEntities[0];
    }

    public function testIndex(): void
    {
        $this->client->request('GET', $this->baseUrl . '/users');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate(): void
    {
        $crawler = $this->client->request('GET', $this->baseUrl . '/users/create');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('submit')->form();
        $form['user_form[name]'] = $this->user->getName();

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testEdit(): void
    {
        $crawler = $this->client->request('GET', sprintf($this->baseUrl . '/users/edit/%d', $this->user->getId()));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('submit')->form();
        $form['user_form[name]'] = $this->user->getName();

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testDelete(): void
    {
        $this->client->request('GET', sprintf($this->baseUrl . '/users/delete/%d', $this->user->getId()));

        $this->assertTrue($this->client->getResponse()->isRedirect('/users'));
    }

    public function testShow(): void
    {
        $this->client->request('GET', sprintf($this->baseUrl . '/users/%d', $this->user->getId()));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testAddToGroup(): void
    {
        $this->client->request(
            'POST', 
            sprintf(
                $this->baseUrl . '/users/add-to-group/%d/%d', 
                $this->user->getId(), 
                $this->group->getId()
            )
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testRemoveFromGroup(): void
    {
        $this->client->request('POST', sprintf($this->baseUrl . '/users/remove-from-group/%d/%d', $this->user->getId(), $this->group->getId()));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}
