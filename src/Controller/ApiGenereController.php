<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Genere;
use App\Form\GenereType;
use App\Repository\GenereRepository;
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
class ApiGenereController extends AbstractFOSRestController
{
  private EntityManagerInterface $emi;
  private GenereRepository $gr;
  public function __construct(EntityManagerInterface $emi, GenereRepository $gr)
  {
    $this->emi = $emi;
    $this->gr = $gr;
  }
  /**
   * @Rest\Get(path="/generes", name="api_llistar_generes")
   * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
   */
  public function llistarGeneres(): View
  {
    return $this->view([
      "Result" => true,
      "Message" => "Llistat de generes",
      "Data" => $this->emi->getRepository(Genere::class)->findAll()
    ], Response::HTTP_ACCEPTED);
  }

  /**
   * @Rest\Delete(path="/genere/{id}", name="api_borrarr_genere")
   * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
   */
  public function borrarGenere(int $id): View
  {
    $genere = $this->obtindreGenereBd($id);
    $genAux=$genere;
    $this->emi->remove($genere);
    $this->emi->flush();
    return $this->view([
      "Result" => true,
      "Message" => "Genere amb id $id",
      "Data" => $genAux
    ], Response::HTTP_ACCEPTED);
  }
  /**
   * @Rest\Get(path="/genere/{id}", name="api_conseguir_genere")
   * @Rest\View(serializerGroups={"genere","videojocs"}, serializerEnableMaxDepthChecks=true)
   */
  public function conseguirGenere(int $id): View
  {
    $genere = $this->obtindreGenereBd($id);
    $vj=[];
    foreach ($genere->getVideojocs() as $videojoc ) {
      array_push($vj,$videojoc);
    }
    return $this->view([
      "Result" => true,
      "Message" => "Genere amb id $id",
      "Data" => $genere
    ], Response::HTTP_ACCEPTED);
  }

  /**
   * @Rest\Post(path="/genere/nou", name="api_insertar_genere")
   * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
   */
  public function insertarGenere(Request $request): View
  {
    $genere = new Genere();
    $form = $this->createForm(GenereType::class, $genere);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    $isSubmited = $form->isSubmitted() && $form->isValid();
    if ($isSubmited) {
      $this->emi->persist($genere);
      $this->emi->flush();
      return $this->view([
        "Result" => true,
        "Message" => "Genere insertat de manera correcta",
        "Data" => $genere,
      ], Response::HTTP_ACCEPTED);
    }
    return $this->view([
      "Result" => false,
      "Message" => "Error el genere no ha pogut ser insertat",
      "Data" => $form
    ], Response::HTTP_NOT_FOUND);
  }

  /**
   * @Rest\Put(path="/genere/{id}/editar", name="api_actualizar_genere")
   * @Rest\View(serializerGroups={"genere"}, serializerEnableMaxDepthChecks=true)
   */
  public function actualizarGenere(Request $request,int $id): View
  {
    $genere = $this->obtindreGenereBd($id);
    $form = $this->createForm(GenereType::class, $genere);
    $data = json_decode($request->getContent(), true);
    $form->submit($data);
    $isSubmited = $form->isSubmitted() && $form->isValid();
    if ($isSubmited) {
      $genere->setGenere($data["genere"]);
      $this->emi->flush();
      return $this->view([
        "Result" => true,
        "Message" => "Genere actualizat de manera correcta",
        "Message" => $data["genere"],
        "Data" => $genere
      ], Response::HTTP_ACCEPTED);
    }
    return $this->view([
      "Result" => false,
      "Message" => "Error el genere no ha pogut ser insertat",
      "Data" => $form
    ], Response::HTTP_NOT_FOUND);
  }
  public function obtindreGenereBd(int $id)
  {
    $genere = $this->gr->find($id);
    if (!$genere) {
      throw $this->createNotFoundException("El genere amb el id $id no existeix");
    }
    return $genere;
  }
}
