<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $UserRepository;

    public function __construct(userRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users/", name="add_user", methods={"POST"})
     */
    public function add(Request $request, UserPasswordEncoderInterface $pass): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $adresse = $data['adresse'];
        $email = $data['email'];
        $avatar = $data['avatar'];
        $solde_conges=$data['solde_conges'];
        $password = $data['password'];
        

        //if (empty($name) || empty($adresse) || empty($email) || empty($avatar) || empty($solde_conges) || empty($password)) {
            //throw new NotFoundHttpException('Expecting mandatory parameters!');
        

        $this->userRepository->saveUser($name, $avatar, $email, $adresse, $solde_conges,$password,$pass);

        return new JsonResponse(['status' => 'user created!'], Response::HTTP_CREATED);
    }
//*****update ******/
       /**
 * @Route("/update/{id}", name="update_customer", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{ $userRepository = $this->getDoctrine()->getRepository(User::class);
    $user = $userRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

    empty($data['name']) ? true : $user->setname($data['name']);
    empty($data['adresse']) ? true : $user->setadresse($data['adresse']);
   
    empty($data['avatar']) ? true : $user->setavatar($data['avatar']);
    empty($data['solde_conges']) ? true : $user->setsoldeconges($data['solde_conges']);
    empty($data['password']) ? true : $user->setpassword($data['password']);

    $updatedUser = $this->userRepository->updateUser($user);

    return new JsonResponse($updatedUser->toArray(), Response::HTTP_OK);
}
 /*****delete****/
 /**
 * @Route("/delete/{id}", name="delete_user", methods={"DELETE"})
 */
public function delete($id): JsonResponse
{
    $user = $this->userRepository->findOneBy(['id' => $id]);

    $this->userRepository->removeUser($user);

    return new JsonResponse(['status' => 'user deleted'], Response::HTTP_NO_CONTENT);
}

}