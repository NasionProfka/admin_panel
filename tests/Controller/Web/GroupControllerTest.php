<?php

namespace App\Tests\Controller\Web;

use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GroupControllerTest extends WebTestCase
{
    private string $baseUrl;
    private Group $group;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->baseUrl = 'http://localhost:40215';
        $this->client = static::createClient();
        $entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $repository = $entityManager->getRepository(Group::class);
        $entities = $repository->findBy([], [], $limit = 1);
        $this->group = $entities[0];
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->baseUrl . '/groups');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Groups');
    }

    public function testCreate(): void
    {
        $crawler = $this->client->request('GET', $this->baseUrl . '/groups/create');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('submit')->form();
        $form['group_form[name]'] = 'New Group';

        $this->client->submit($form);
        $this->assertResponseRedirects('/groups');
    }

    public function testShow(): void
    {
        $this->client->request('GET', '/groups/' . $this->group->getId());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->group->getName());
    }

    public function testEdit(): void
    {
        $crawler = $this->client->request('GET', $this->baseUrl . '/groups/edit/' . $this->group->getId());

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('submit')->form();
        $form['group_form[name]'] = 'Updated Group';

        $this->client->submit($form);
        $this->assertResponseRedirects('/groups');
    }

    public function testDelete(): void
    {
        $this->client->request('GET', '/groups/delete/' . $this->group->getId());
        $this->assertResponseRedirects('/groups');
    }
}