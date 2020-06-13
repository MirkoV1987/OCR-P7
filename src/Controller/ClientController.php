<?php

namespace App\Controller;

use App\Entity\Client;
use Swagger\Annotations as SWG;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Security as nSecurity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("api/", name="client_")
 */
class ClientController extends AbstractController
{
    /**
     * GET Users of a specific Client
     * @Route("clients/{id}", name="show", methods={"GET"})
     * @Groups({"show"})
     * @SWG\Parameter(
     *   name="id",
     *   description="Id of the users list to get",
     *   in="path",
     *   required=true,
     *   type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Client::class))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token"
     * )
     * @SWG\Response(
     *     response=403,
     *     description="ACCESS DENIED"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="NOT FOUND"
     * )
     * @SWG\Tag(name="Client")
     * @nSecurity(name="Bearer")
     * @Security("user === client")
     * @param Client $client
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Client $client, SerializerInterface $serializer) : JsonResponse
    {
        //Cache control
        $response = new JsonResponse;
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        
        $data = $serializer->serialize($client, 'json');

        $response->setJson($data, JsonResponse::HTTP_OK, [], true);

        return $response;
    }
}