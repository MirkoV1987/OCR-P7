<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\Pagination;
use Swagger\Annotations as SWG;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("api/", name="product_")
 */
class ProductController extends AbstractController
{
    /**
     * GET Details about a specific Product
     * @Route("products/{id}", name="show", methods={"GET"})
     * @Groups({"details"})
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
        $data = $serializer->serialize($product, 'json', SerializationContext::create()->setGroups(array('details')));
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * GET Products List
     * @Route("products", name="list", methods={"GET"})
     * @Groups({"list"})
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
     * @param Pagination $pagination
     * @return JsonResponse
     * @throws \Doctrine\DBAL\DBALException
     */
    public function list(SerializerInterface $serializer, Pagination $pagination, Request $request) : JsonResponse
    {
        //Cache control
        $response = new JsonResponse;
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $limit = $request->query->get('limit', $this->getParameter('default_product_limit'));
        $page = $request->query->get('page', 1);
        $route = $request->attributes->get('_route');
        $criteria = !empty($request->query->get('name')) ? ['name' => $request->query->get('name')] : [];

        $pagination->setEntityClass(Product::class)->setRoute($route);
        $pagination->setCurrentPage($page)->setLimit($limit);
        $pagination->setCriteria($criteria);

        $paginated = $pagination->getData();
        $data = $serializer->serialize($paginated, 'json', SerializationContext::create()->setGroups(array('Default', 'list')));

        $response->setJson($data, JsonResponse::HTTP_OK, [], true);

        return $response;
    }
}
        