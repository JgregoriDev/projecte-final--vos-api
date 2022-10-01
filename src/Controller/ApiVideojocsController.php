<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Videojoc;
use App\Form\VideojocType;
use App\Repository\VideojocRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("/api/v1")
 */
class ApiVideojocsController extends AbstractFOSRestController
{
  private EntityManagerInterface $emi;
  private VideojocRepository $vr;
  public function __construct(EntityManagerInterface $emi, VideojocRepository $vr)
  {
    $this->emi = $emi;
    $this->vr = $vr;
  }
  /**
   * @Rest\Get(path="/videojocs", name="api_llistar_videojocs")
   * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
   */
  public function llistarVideojocs()
  {

    return $this->view(["Resultat" => true, "Misatge" => "Llistat de videojocs", "Data" => $this->vr->findAll()], Response::HTTP_ACCEPTED);
  }

  /**
   * @Rest\Get(path="/videojoc/{id}", name="api_conseguir_videojoc")
   * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
   */
  public function conseguirVideojoc(int $id)
  {
    $videojoc = $this->obtindreJocBd($id);
    var_dump($videojoc);
    return $this->view(["Resultat" => true, "Misatge" => "Videojoc amb id $id", "Data" => $videojoc], Response::HTTP_ACCEPTED);
  }

  /**
   * @Rest\Post(path="/videojoc/nou", name="api_insertar_videojoc")
   * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
   */
  public function insertarVideojoc(Request $request)
  {
    $videojoc = new Videojoc();
    $form = $this->createForm(VideojocType::class, $videojoc);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    $isSubmited = $form->isSubmitted() && $form->isValid();
    if ($isSubmited) {
      $this->emi->persist($videojoc);
      $this->emi->flush();
      return $this->view([
        "Resultat" => true,
        "Misatge" => "El videojoc ha sigut creat.",
        "Data" => $videojoc
      ], Response::HTTP_ACCEPTED);
    }
    return $this->view(["Resultat" => false, "Misatge" => "El videojoc no ha pogut ser creat", "Data" => $form], Response::HTTP_BAD_REQUEST);
  }

  /**
   * @Rest\Delete(path="/videojoc/{id}/borrar", name="api_borrar_videojoc")
   * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
   */
  public function borrarVideojoc(Request $request, int $id)
  {
    $videojoc = $this->obtindreJocBd($id);
    $this->emi->remove($videojoc);
    $this->emi->flush();
    
    return $this->view([
      "Resultat" => false,
      "Misatge" => "El videojoc amb id $id ha sigut borrat de manera correcta.",
      "Data" => $videojoc,
    ], Response::HTTP_OK);
  }

  /**
   * @Rest\Put(path="/videojoc/{id}/editar", name="api_modificar_videojoc")
   * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
   */
  public function modificarVideojoc(Request $request,int $id)
  {
    $videojoc = $this->obtindreJocBd($id);
    $form = $this->createForm(VideojocType::class, $videojoc);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    $isSubmited = $form->isSubmitted() && $form->isValid();
    if ($isSubmited) {
      
      $this->emi->flush();
      return $this->view([
        "Resultat" => true,
        "Misatge" => "El videojoc ha sigut actualizat.",
        "Data" => $videojoc,
      ], Response::HTTP_ACCEPTED);
    }
    return $this->view(["Resultat" => false, "Misatge" => "El videojoc no ha pogut ser creat", "Data" => $form], Response::HTTP_BAD_REQUEST);
  }
    /**
   * @Rest\Get(path="/videojoc/buscar/{titol}", name="api_conseguir_videojoc")
   * @Rest\View(serializerGroups={"videojoc"}, serializerEnableMaxDepthChecks=true)
   */
  public function conseguirVideojocBuscantElTitol(String $titol)
  {
    $videojocs = $this->vr->obtindreJocBuscanElTitol($titol);
    return $this->view(["Resultat" => true, "Misatge" => "Videojoc amb titol $titol", "Data" => $videojocs], Response::HTTP_ACCEPTED);
  }
  public function obtindreJocBd(int $id)
  {
    $videojoc = $this->vr->find($id);
    if (!$videojoc) {
      throw $this->createNotFoundException("El videojoc amb el id $id no existeix");
    }
    return $videojoc;
  }
}
