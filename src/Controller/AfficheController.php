<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfficheController extends AbstractController
{private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/affiche/{id}", name="get_one_user",methods={"GET"})
     */
    public function get($id): JsonResponse
    {  $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(['id' => $id]);
        $data = [
         'id' => $user->getId(),
            'name' => $user->getname(),
            'avatar' => $user->getAvatar(),
            'email' => $user->getEmail(),
            'adresse' => $user->getAdresse(),
            'soldes_conges' =>$user->getSoldeConges()
        ];
    
        return new JsonResponse($data, Response::HTTP_OK);


        
    }
/**
 * @Route("/all", name="get_all_users", methods={"GET"})
 */
public function getAll(Request $request): JsonResponse
{$data = json_decode($request->getContent(), true);
    $users = $this->userRepository->findAll();
    $data = [];

    foreach ($users as $user) {
        $data[] = [
            'id' => $user->getId(),
            'Username' => $user->getUsername(),
            'avatar' => $user->getAvatar(),
            'email' => $user->getEmail(),
            'adresse' => $user->getAdresse(),
            'soldes_conges' =>$user->getSoldeConges()
        ];
    }

    return new JsonResponse($data, Response::HTTP_OK);
}


}
