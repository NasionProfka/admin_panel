<?php

namespace App\Tests\Controller\Api;

use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiGroupControllerTest extends WebTestCase
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

    public function testCreateGroup(): void
    {        
        $this->client->request(
            Request::METHOD_POST,
            $this->baseUrl . '/api/groups/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Group'])
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $this->assertJsonStringEqualsJsonString(
            '{"message":"Group added successfully"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDeleteGroup(): void
    {
        $this->client->request(
            Request::METHOD_DELETE, 
            $this->baseUrl . '/api/groups/delete/' . $this->group->getId()
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertJsonStringEqualsJsonString(
            '{"message":"Group deleted successfully"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testDeleteNonexistentGroup(): void
    {
        $this->client->request(Request::METHOD_DELETE, $this->baseUrl . '/api/groups/delete/99999999');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $this->assertJsonStringEqualsJsonString(
            '{"message":"Group not found!"}',
            $this->client->getResponse()->getContent()
        );
    }
}