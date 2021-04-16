<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Demande;
use App\Repository\DemandeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DemandeController extends AbstractController
{private $DemandeRepository;

    public function __construct(DemandeRepository $DemandeRepository)
    {
        $this->DemandeRepository = $DemandeRepository;
    }

    /**
     * @Route("/demande/", name="add_demande", methods={"POST"})
     */
    public function add(Request $request,UserRepository $userRepository): JsonResponse
    {   
        $data = json_decode($request->getContent(), true);
      

        $date_debut = $data['date_debut'];
        $date_fin = $data['date_fin'];
        $etat = $data['etat'];
        $user = $this->getDoctrine()->getRepository(User::class)->find($data['user_id']);
    
        

        if (empty($date_debut) || empty($date_fin) || empty($etat) || empty($user)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->DemandeRepository->saveDemande($date_debut, $date_fin, $etat, $user);

        return new JsonResponse(['status' => 'demande created!'], Response::HTTP_CREATED);
    }
    /****affiche***/
    /**
 * @Route("/alldemande", name="get_all_demandes", methods={"GET"})
 */
public function getAll(Request $request, UserRepository $userRepository): JsonResponse
{$data = json_decode($request->getContent(), true);
    $demandes = $this->DemandeRepository->findAll();
    $data = [];
    foreach ($demandes as $demande) {
        $data[] = [
            'id' => $demande->getId(),
            'date_debut' => $demande->getDateDebut(),
            'date_fin' => $demande->getDateFin(),
            'etat' => $demande->getEtat(),
            'utlisateur' => $demande->getUser()->getUsername()
           
        ];
    }

    return new JsonResponse($data, Response::HTTP_OK);
}
//*****update ******/
       /**
 * @Route("/updatedemande/{id}", name="update_demande", methods={"PUT"})
 */
public function update($id, Request $request): JsonResponse
{ $DemandeRepository = $this->getDoctrine()->getRepository(Demande::class);
    $demande = $DemandeRepository->findOneBy(['id' => $id]);
    $data = json_decode($request->getContent(), true);

    empty($data['date_debut']) ? true : $demande->setDateDebut($data['date_debut']);
    empty($data['date_fin']) ? true : $demande->setDateFin($data['date_fin']);
    empty($data['etat']) ? true : $demande->setEtat($data['etat']);
 
    $updateDemande = $this->DemandeRepository->updateDemande($demande);

    return new JsonResponse($updateDemande->toArray(), Response::HTTP_OK);
}
 /*****delete****/
 /**
 * @Route("/deletedemande/{id}", name="delete_demande", methods={"DELETE"})
 */
public function delete($id): JsonResponse
{
    $demande = $this->DemandeRepository->findOneBy(['id' => $id]);

    $this->DemandeRepository->removeUser($demande);

    return new JsonResponse(['status' => 'demande deleted'], Response::HTTP_NO_CONTENT);
}

}