<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/api/v1")
 */
class ApiUserController extends AbstractFOSRestController
{
    public function __construct(
        public EntityManagerInterface $emi,
        public UserRepository $ur,
        public UserPasswordHasherInterface $uphi
    ) {
        # code...
    }
    /**
     * @Rest\Get(path="/usuaris", name="api_llistar_usuaris")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function index(): View
    {
        return $this->view(["Result" => true, 'Message' => "Llistat de usuaris", "Data" => $this->ur->findAll()], Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Delete(path="/usuari/{id}/borrar", name="api_borrar_usuari")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function borrarGenere(int $id): View
    {
        $user = $this->obtindrUsuariBd($id);
        $userAux = $user;
        $this->emi->remove($user);
        $this->emi->flush();
        return $this->view([
            "Result" => true,
            "Message" => "Usuari amb id $id",
            "Data" => $userAux
        ], Response::HTTP_ACCEPTED);
    }
    /**
     * @Rest\Get(path="/genere/{id}", name="api_conseguir_usuari")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function conseguirGenere(int $id): View
    {
        //$genere = $this->obtindreGenereBd($id);
        $user = $this->us->find($id);

        return $this->view([
            "Result" => true,
            "Message" => "Usuari amb id $id",
            "Data" => $user
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Post(path="/usuari/nou", name="api_insertar_usuari")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function insertarUsuari(Request $request): View
    {
        $usuari = new User();
        $form = $this->createForm(UserType::class, $usuari);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $isSubmited = $form->isSubmitted() && $form->isValid();
        if ($isSubmited) {
            $password=$this->uphi->hashPassword($usuari,$data['password']);
            $usuari->setRoles(["ROLE_USER"])
            ->setPassword($password);
            $this->emi->persist($usuari);
            $this->emi->flush();
            return $this->view([
                "Result" => true,
                "Message" => "Usuari insertat de manera correcta",
                "Data" => $usuari,
            ], Response::HTTP_ACCEPTED);
        }
        return $this->view([
            "Result" => false,
            "Message" => "Error el genere no ha pogut ser insertat",
            "Data" => $form
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Put(path="/usuari/{id}/editar", name="api_actualizar_usuari")
     * @Rest\View(serializerGroups={"user"}, serializerEnableMaxDepthChecks=true)
     */
    public function actualizarUsuari(Request $request, int $id): View
    {
        $usuari = $this->obtindrUsuariBd($id);
        $form = $this->createForm(UserType::class, $usuari);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        $isSubmited = $form->isSubmitted() && $form->isValid();
        if ($isSubmited) {
            $password=$this->uphi->hashPassword($usuari,$data['password']);
            $usuari->setEmail($data["email"])
            ->setPassword($password)
            ->setRoles(["ROLE_USER"]);
            $this->emi->flush();
            return $this->view([
                "Result" => true,
                "Message" => "Usuari actualizat de manera correcta",
                "Data" => $usuari
            ], Response::HTTP_ACCEPTED);
        }
        return $this->view([
            "Result" => false,
            "Message" => "Error l'usuari no ha pogut ser insertat",
            "Data" => $form
        ], Response::HTTP_NOT_FOUND);
    }
    public function obtindrUsuariBd(int $id)
    {
        $usuari = $this->ur->find($id);
        if (!$usuari) {
            throw $this->createNotFoundException("El usuari amb el id $id no existeix");
        }
        return $usuari;
    }
}
