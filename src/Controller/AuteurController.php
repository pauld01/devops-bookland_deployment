<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Livre;
use App\Form\AuteurType;
use App\Form\ApdateAllBookNote;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/auteur")
 */
class AuteurController extends AbstractController
{
    /**
     * @Route("/", name="auteur_index", methods={"GET"})
     */
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="auteur_show", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function show(Auteur $auteur, Request $request, AuteurRepository $auteurRepository): Response
    {
        $genresParDate = array();
        $em = $this->getDoctrine()->getManager();

        $repoLivre = $em->getRepository(Livre::class);
        $repoAuteur = $em->getRepository(Auteur::class);
        $livres = $repoAuteur->find($auteur)->getLivre();

        $listLivre = array();

        foreach ($livres as $livre) {
            $listLivre[] = $livre;
            $genres = $livre->getGenre();
            $dateParution = $livre->getDateDeParution();

            foreach ($genres as $genre) {
                $genresParDate[] = $genre;
            }
        }

        $genresParDate=array_unique($genresParDate);

        $updateForm = $this->createForm(ApdateAllBookNote::class);

        if ($updateForm->handleRequest($request)->isSubmitted() && $updateForm->isValid()) {
            $valeur = $updateForm->getData();

            $add = $valeur['val'];

            if($add == 0)
                $add=1;

            foreach ($listLivre as $l){
                $old = $l->getNote();

                if ($old+$add > 20){
                    $l->setNote(20);
                }
                else{
                    $l->setNote($old+$add);
                }
                $em->persist($l);
            }
            $em->flush();

            return $this->redirectToRoute('auteur_index', ['auteurs' => $auteurRepository->findAll()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('auteur/show.html.twig', [
            'auteur' => $auteur, 'genres' => $genresParDate, 'Updateform' => $updateForm->createView(), 'livre' => $livres
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auteur_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('auteur/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="auteur_delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
            $listLivre = $auteur->getLivre();

            foreach ($listLivre as $livre){
                $entityManager->remove($livre);
            }
            $entityManager->remove($auteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('auteur_index', [], Response::HTTP_SEE_OTHER);
    }

    public function writeSupN() // Fonction qui gère la fonctionnalité 16
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Auteur::class);
        $ListAuteur = $repo->findAll();
        $final = array(); // Tableau qui va contenir les auteurs qui on écrit 3 livre différent
        foreach ($ListAuteur as $auteur){
            $Livre = $auteur->getLivre();
            if (count($Livre) >= 3 ){
                $final[] = $auteur;
            }  
        }
        return $this->render('auteur/writeSupN.html.twig',array('list' => $final));
    }

    public function searchGenreByChrono(){ // Fonction qui gère la fonctionnalité 20

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Auteur::class);
        $ListAuteur = $repo->findAll();


        foreach ($ListAuteur as $auteur){
            $nbL = 0;

            $listLivre = $auteur->getLivre();
            $tmp = array();
            foreach ($listLivre as $livre){
                $nbL+=1;
                $listGenre = $livre->getGenre();
                foreach ($listGenre as $genre){
                    $name = $genre->getNom();
                    $date = $livre->getDateDeParution();

                    if (array_key_exists($name,$tmp)){
                        if ($tmp[$name] <= $date){
                            $tmp[$name] = $date;
                        }
                    }
                    else{
                        $tmp[$name] = $date;
                    }
                        
                }
                arsort($tmp);
            }

            if ($nbL != 0){
                $result[$auteur->getId()]=$tmp;
            }
                

            $nbL=0;
        }

        $final=array();

        foreach ( $result as $key => $value){
            $aut = $repo->find($key);
            $final[] = array('auteur' => $aut, 'value' => $value);
        }

        return $this->render('auteur/searchGenreByChrono.html.twig',array('list' => $final));

    }
}
