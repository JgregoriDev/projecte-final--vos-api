<?php

namespace App\Controller;

use App\Entity\Plataforma;
use App\Form\PlataformaType;
use App\Repository\PlataformaRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/api/v1")
 */
class ApiPlataformaController extends AbstractFOSRestController
{
  private EntityManagerInterface $emi;
  private PlataformaRepository $pr;
  public function __construct(EntityManagerInterface $emi, PlataformaRepository $pr)
  {
    $this->emi = $emi;
    $this->pr = $pr;
  }
  /**
   * @Rest\Get(path="/plataformes", name="api_llistar_plataformes")
   * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
   */
  public function llistarPlataformes()
  {

    return $this->view([
      "Resultat" => true,
      "Misatge" => "Llistat de plataformes",
      "Data" => $this->pr->findAll()
    ], Response::HTTP_ACCEPTED);
  }

  /**
   * @Rest\Get(path="/plataforma/{id}", name="api_conseguir_plataforma")
   * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
   */
  public function conseguirPlataformaVideojoc(int $id)
  {
    $plataforma = $this->obtindrePlataformaBd($id);
    return $this->view([
      "Resultat" => true,
      "Misatge" => "Plataforma amb id $id",
      "Data" => $plataforma
    ], Response::HTTP_ACCEPTED);
  }

  /**
   * @Rest\Post(path="/plataforma/nou", name="api_insertar_plataforma")
   * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
   */
  public function insertarPlataforma(Request $request)
  {
    $plataforma = new Plataforma();
    $form = $this->createForm(PlataformaType::class, $plataforma);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    $isSubmited = $form->isSubmitted() && $form->isValid();
    if ($isSubmited) {
      $this->emi->persist($plataforma);
      $this->emi->flush();
      return $this->view([
        "Resultat" => true,
        "Misatge" => "El plataforma ha sigut creat.",
        "Data" => $plataforma
      ], Response::HTTP_ACCEPTED);
    }
    return $this->view([
      "Resultat" => false,
      "Misatge" => "El videojoc no ha pogut ser creat",
      "Data" => $form
    ], Response::HTTP_BAD_REQUEST);
  }

  /**
   * @Rest\Delete(path="/plataforma/{id}/borrar", name="api_borrar_plataforma")
   * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
   */
  public function borrarPlataforma(Request $request, int $id)
  {
    $plataforma = $this->obtindrePlataformaBd($id);
    $this->emi->remove($plataforma);
    $this->emi->flush();
    return $this->view([
      "Resultat" => false,
      "Misatge" => "La plataforma amb id $id ha sigut borrat de manera correcta.",
      "Data" => $plataforma,
    ], Response::HTTP_OK);
  }

  /**
   * @Rest\Put(path="/plataforma/{id}/editar", name="api_modificar_plataforma")
   * @Rest\View(serializerGroups={"plataforma"}, serializerEnableMaxDepthChecks=true)
   */
  public function modificarPlataforma(Request $request, int $id)
  {
    $plataforma = $this->obtindrePlataformaBd($id);
    $form = $this->createForm(PlataformaType::class, $plataforma);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    $isSubmited = $form->isSubmitted() && $form->isValid();
    if ($isSubmited) {
      $plataforma->setPlataforma($data['plataforma']);
      $this->emi->flush();
      return $this->view([
        "Resultat" => true,
        "Misatge" => "El plataforma ha sigut actualizat.",
        "Data" => $plataforma,
      ], Response::HTTP_ACCEPTED);
    }
    return $this->view([
      "Resultat" => false,
      "Misatge" => "El videojoc no ha pogut ser creat",
      "Data" => $form
    ], Response::HTTP_BAD_REQUEST);
  }

  public function obtindrePlataformaBd(int $id)
  {
    $videojoc = $this->pr->find($id);
    if (!$videojoc) {
      throw $this->createNotFoundException("La plataforma amb el id $id no existeix");
    }
    return $videojoc;
  }
}
