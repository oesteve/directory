<?php

namespace Directory\UI\Controller\Rest;

use Directory\Application\Command\CommandBus;
use Directory\Application\Command\User\CreateUser;
use Directory\Application\Command\User\DeleteUser;
use Directory\Application\Command\User\UpdateUser;
use Directory\Application\Query\QueryBus;
use Directory\Application\Query\User\DTO\UserDTO;
use Directory\Application\Query\User\GetUserById;
use Directory\Application\Query\User\SearchUsers;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Serializer\SerializerInterface;

class UserController
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createUser(Request $request, CommandBus $commandBus): Response
    {
        $data = $this->parseJsonResponse($request);
        $name = $this->getKeyOrFail($data, 'name');
        $properties = $this->getKeyOrFail($data, 'properties');

        $id = Uuid::uuid4()->toString();

        $commandBus->dispatch(new CreateUser($id, $name, $properties));

        return new JsonResponse(['id' => $id], Response::HTTP_OK);
    }

    public function getUser(Request $request, QueryBus $queryBus): Response
    {
        $id = $request->get('id');

        /** @var UserDTO $user */
        $user = $queryBus->query(new GetUserById($id));

        return $this->createJsonResponse($user);
    }

    public function updateUser(Request $request, CommandBus $commandBus): Response
    {
        $data = $this->parseJsonResponse($request);

        $id = $this->getKeyOrFail($data, 'id');
        $name = $this->getKeyOrFail($data, 'name');
        $props = $this->getKeyOrFail($data, 'properties');

        /* @var UserDTO $user */
        $commandBus->dispatch(new UpdateUser($id, $name, $props));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function deleteUser(Request $request, CommandBus $commandBus): Response
    {
        $id = $this->getParamOrFail($request, 'id');

        $commandBus->dispatch(new DeleteUser($id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    public function searchUsers(Request $request, QueryBus $queryBus): Response
    {
        $query = $this->getParamOrFail($request, 'query');

        /** @var UserDTO[] $users */
        $users = $queryBus->query(new SearchUsers($query));

        if (empty($users)) {
            return JsonResponse::create([], Response::HTTP_NOT_FOUND);
        }

        return $this->createJsonResponse($users);
    }

    private function parseJsonResponse(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    private function createJsonResponse($data): JsonResponse
    {
        $jsonData = $this->serializer->serialize($data, 'json');

        return JsonResponse::fromJsonString($jsonData);
    }

    private function getKeyOrFail(array $data, string $paramName)
    {
        if (!array_key_exists($paramName, $data)) {
            throw new InvalidParameterException("The property $paramName must be provided");
        }

        return $data[$paramName];
    }

    private function getParamOrFail(Request $request, string $paramName)
    {
        $param = $request->get($paramName);

        if (!$param) {
            throw new InvalidParameterException("The param $paramName must be provided");
        }

        return $param;
    }
}
