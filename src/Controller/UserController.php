<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use DateTime;
use App\Repository\UserRepository;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Security as nSecurity;

/**
 * Class UserController
 * @package App\Controller
 * @Route("api/", name="client_users_")
 */
class UserController extends AbstractController
{
    // /**
    //  * Get Users client list
    //  * @Route("users/{id}", name="list", methods={"GET"})
    //  * @SWG\Parameter(
    //  *   name="id",
    //  *   description="Id of the users client list to get",
    //  *   in="path",
    //  *   required=true,
    //  *   type="integer"
    //  * )
    //  * @SWG\Response(
    //  *     response=200,
    //  *     description="OK",
    //  *     @SWG\Schema(
    //  *         type="array",
    //  *         @SWG\Items(ref=@Model(type=User::class))
    //  *     )
    //  * )
    //  * @SWG\Response(
    //  *     response=401,
    //  *     description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token"
    //  * )
    //  * @SWG\Response(
    //  *     response=403,
    //  *     description="ACCESS DENIED"
    //  * )
    //  * @SWG\Response(
    //  *     response=404,
    //  *     description="NOT FOUND"
    //  * )
    //  * @SWG\Tag(name="User")
    //  * @param User $user
    //  * @param SerializerInterface $serializer
    //  * @return JsonResponse
    //  */
    // public function usersClientList(User $userClient, SerializerInterface $serializer, UserRepository $userRepo) : JsonResponse
    // {
    //     $userClient = $serializer->serialize($userRepo->findBy(['client' => $this->getUser()]), 'json');
    //     $data = $serializer->serialize($userClient, 'json');
    //     return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    // }

    /**
     * Get details about a specific user
     * @Route("users/{id}", name="details", methods={"GET"})
     * @Groups({"users_details"})
     * @SWG\Parameter(
     *   name="id",
     *   description="Id of the user to get",
     *   in="path",
     *   required=true,
     *   type="integer"
     * )
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
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
     * @SWG\Tag(name="User")
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function userDetail(User $user, SerializerInterface $serializer) : JsonResponse
    {
        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('users_details')));
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    /**
     * User creation
     * @Route("users", name="create", methods={"POST"})
     * 
     * @SWG\Parameter(
     *   name="User",
     *   description="Fields to provide to create an user",
     *   in="body",
     *   required=true,
     *   type="string",
     *   @SWG\Schema(
     *     type="object",
     *     title="User field",
     *     @SWG\Property(property="username", type="string"),
     *     @SWG\Property(property="email", type="string")
     *     )
     * )
     * @SWG\Response(
     *     response=201,
     *     description="CREATED",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="BAD REQUEST"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="UNAUTHORIZED - JWT Token not found | Expired JWT Token | Invalid JWT Token"
     * )
     * @SWG\Tag(name="User")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator) : JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $errors = $validator->validate($user);
        if(count($errors) > 0) {
            $data = $serializer->serialize($errors, 'json');
            return new JsonResponse($data, 400, [], true);
        }

        //$client = $this->getDoctrine()->getRepository(User::class)->findOneBy(['client' => $user->getClient()]);
        $user->setDateAdd(new DateTime())
             ->setRoles(['ROLE_USER'])
             ->setPassword('dsdd$%&');
          //   ->setClient($client);
        
        $em->persist($user);
        $em->flush();

        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('Default')));
        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }

    /**
     * User deletion
     * @Route("users/{id}", name="delete", methods={"DELETE"})
     * @SWG\Parameter(
     *   name="id",
     *   description="Id of the user to delete",
     *   in="path",
     *   required=true,
     *   type="integer"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="NO CONTENT"
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
     * @SWG\Tag(name="User")
     * @nSecurity(name="Bearer")
     * @Security("user === userC.getClient() || is_granted('ROLE_ADMIN')")
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function delete(User $userC, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($userC);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}