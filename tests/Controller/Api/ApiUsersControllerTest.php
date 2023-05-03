<?php

namespace App\Tests\Controller\Api;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiUsersControllerTest extends WebTestCase
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

    public function testCreate(): void
    {
        $this->client->request(
            'POST', 
            $this->baseUrl . '/api/users/create', 
            [], 
            [], 
            [], 
            json_encode([
                "name" => $this->user->getName()
            ]
        ));

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User added successfully', $responseData['message']);
    }

    public function testDelete(): void
    {
        $this->client->request('DELETE', sprintf($this->baseUrl . '/api/users/delete/%d', $this->user->getId()));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User deleted successfully', $responseData['message']);
    }

    public function testAddToGroup(): void
    {
        $this->client->request('POST', sprintf($this->baseUrl . '/api/users/add-to-group/%d/%d', $this->user->getId(), $this->group->getId()));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('User added to the group successfully!', $responseData['message']);
    }

    public function testRemoveFromGroup(): void
    {
        $this->client->request('POST', sprintf($this->baseUrl . '/api/users/remove-from-group/%d/%d', $this->user->getId(), $this->group->getId()));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals('User removed from the group successfully', $responseData);
    }
}
