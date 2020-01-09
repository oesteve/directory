<?php

namespace Directory\Tests\UI\Rest;

use Directory\Application\Fixture\UserFixtures;
use Directory\Tests\UI\BaseFunctionalTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends BaseFunctionalTest
{
    public function testCreateUser(): void
    {
        $request = Request::create(
            '/api/user',
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            json_encode(['name' => 'Bob', 'properties' => []])
        );

        // Create new user
        $response = $this->executeRequest($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = $this->json($response);
        $this->assertNotNull($data['id']);
    }

    public function testUpdateUser(): void
    {
        $request = Request::create(
            '/api/user',
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            json_encode(['name' => 'Bob', 'properties' => []])
        );
        $response = $this->executeRequest($request);
        $data = $this->json($response);
        $id = $data['id'];

        $request = Request::create(
            '/api/user',
            Request::METHOD_PUT,
            [],
            [],
            [],
            [],
            json_encode(['id' => $id, 'name' => 'Bob II', 'properties' => ['bar' => 'foo']])
        );
        $response = $this->executeRequest($request);
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->executeRequest(Request::create("/api/user/$id"));
        $this->assertEquals(200, $response->getStatusCode());

        $data = $this->json($response);
        $this->assertEquals(['id' => $id, 'name' => 'Bob II', 'properties' => ['bar' => 'foo']], $data);
    }

    public function testGetUser(): void
    {
        // Create a new user
        $createUserRequest = Request::create(
            '/api/user',
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            json_encode(['name' => 'Bob', 'properties' => ['bar' => 'foo']])
        );

        $creteUserResponse = $this->executeRequest($createUserRequest);
        $createUserData = $this->json($creteUserResponse);
        $id = $createUserData['id'];

        // Assert the created user
        $response = $this->executeRequest(Request::create("/api/user/$id"));
        $this->assertEquals(200, $response->getStatusCode());

        $data = $this->json($response);

        $this->assertNotNull($data['id']);
        $this->assertEquals(['id' => $id, 'name' => 'Bob', 'properties' => ['bar' => 'foo']], $data);
    }

    public function testDeleteUser(): void
    {
        // Create a new user
        $createUserRequest = Request::create(
            '/api/user',
            Request::METHOD_POST,
            [],
            [],
            [],
            [],
            json_encode(['name' => 'Bob', 'properties' => ['bar' => 'foo']])
        );

        $creteUserResponse = $this->executeRequest($createUserRequest);
        $createUserData = $this->json($creteUserResponse);
        $id = $createUserData['id'];

        $response = $this->executeRequest(Request::create("/api/user/$id", Request::METHOD_DELETE));
        $this->assertEquals(204, $response->getStatusCode());

        $response = $this->executeRequest(Request::create("/api/user/$id"));
        $this->assertEquals(500, $response->getStatusCode()); // TODO 500 or 404 ?
    }

    public function testGetUserWithInvalidId(): void
    {
        $response = $this->executeRequest(Request::create('/api/user/invalid-id'));
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testSearchUsersWhenRepositoryIsEmpty(): void
    {
        $response = $this->executeRequest(
            Request::create(
                '/api/user',
                Request::METHOD_GET,
                ['query' => 'azul']
            )
        );

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testSearchUsers(): void
    {
        foreach (UserFixtures::sampleData() as $data) {
            // Create a new user
            $createUserRequest = Request::create(
                '/api/user',
                Request::METHOD_POST,
                [],
                [],
                [],
                [],
                json_encode(['name' => $data[0], 'properties' => $data[1]])
            );
            $this->executeRequest($createUserRequest);
        }

        $response = $this->executeRequest(
            Request::create(
                '/api/user',
                Request::METHOD_GET,
                ['query' => 'azul']
            )
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $this->json($response));
    }
}
