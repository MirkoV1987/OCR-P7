<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController
{
    /**
     * @Route("/api/login", name="login", methods={"POST"})
     * @SWG\Parameter(
     *   name="Login",
     *   description="Fields to provide to sign in and get a token",
     *   in="body",
     *   required=true,
     *   type="string",
     *   @SWG\Schema(
     *     type="object",
     *     title="Login field",
     *     @SWG\Property(property="email", type="string"),
     *     @SWG\Property(property="password", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *      type="string",
     *      title="Token",
     *      @SWG\Property(property="token", type="string"),
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request - Invalid JSON",
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     * )
     * @SWG\Tag(name="Authentication")
     * @return JsonResponse
     */
    public function login() : JsonResponse
    {
        $client = $this->getClient();

        return $this->json(array("email" => $client->getEmail(), 'roles' => $client->getRoles() ));
    }
}