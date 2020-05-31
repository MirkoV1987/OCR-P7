<?php

namespace App\Controller;

use App\Entity\Product;
use Swagger\Annotations as SWG;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Nelmio\ApiDocBundle\Annotation\Security as nSecurity;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("api/", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * Get details about a specific Product
     * @Route("products/{id}", name="show", methods={"GET"})
     * @SWG\Parameter(
     *   name="id",
     *   description="Id of the product to get",
     *   in="path",
     *   required=true,
     *   type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="NOT FOUND"
     * )
     * @SWG\Tag(name="Product")
     * @param Product $product
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function show(Product $product, SerializerInterface $serializer) : JsonResponse
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneById(1);
        $data = $serializer->serialize($product, 'json');
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * Get products list
     * @Route("products", name="list", methods={"GET"})
     * @SWG\Parameter(
     *   name="name",
     *   description="The mobile name to search",
     *   in="query",
     *   type="string"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *      @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Product::class))
     *     )
     * )
     * @SWG\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token"
     * )
     * @SWG\Tag(name="Product")
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     */
    public function list(SerializerInterface $serializer, Request $request) : JsonResponse
    {
        $response = new JsonResponse();
        
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $data = $serializer->serialize($products, 'json');

        $response->setJson($data);

        return $response;
    }

}