<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/livre")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/", name="livre_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="livre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livre_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Livre $livre): Response
    {

        return $this->render('livre/show.html.twig', [
            'livre' => $livre, 'auteurs' => $livre->getAuteur(), 'genres' => $livre->getGenre()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livre_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livre_delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livre_index', [], Response::HTTP_SEE_OTHER);
    }

    public function natioDif() // Fonction qui gère la fonctionnalité 14
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Livre::class);
        $ListLivre = $repo->findAll();
        $final = array(); // Tableau qui va contenir les livres avec des auteurs de nationalitées différentes
        

        foreach ($ListLivre as $livre){
            $auteur = $livre->getAuteur();

            $test = 1;
            $natio = array();
            $tab = array();

            foreach ($auteur as $n){
                $natio[]=$n->getNationalite();
            }
            $tab = array_count_values($natio);

            foreach ($tab as $k => $v){

                if ($v > 1)
                    $test = 2;
                
            }

            if ($test == 1)
                $final[] = $livre;

        }
        return $this->render('livre/natioDif.html.twig',array('list' => $final));
    }

    public function parite(){ // Fonction qui gère la fonctionnalité 17
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Livre::class);

        $listLivre = $repo->findAll();

        $final = array();

        foreach ($listLivre as $livre){
            $listAuteur = $livre->getAuteur();
            $NBF = 0;
            $NBH = 0;
            $pourcentageH = 0;
            $pourcentageF = 0;
            $total = 0;
            
            foreach ($listAuteur as $auteur){
                $s = $auteur->getSexe();
                if (strcmp($s,"M") == 0){
                    $NBH+=1;
                }
                else if (strcmp($s,"F") == 0){
                    $NBF+=1;
                }
            }

            $total=$NBF+$NBH;

            if ($total != 0){
                $pourcentageH = ($NBH*100)/$total;
                $pourcentageF = ($NBF*100)/$total;
            }

            

            if ( (($pourcentageH >= 49) && ($pourcentageH <= 51)) ||  (($pourcentageF >= 49) && ($pourcentageF <= 51)) ){
                $stat = array('livre' => $livre, 'total' => $total, 'pourcentageH' => $pourcentageH, 'pourcentageF' => $pourcentageF, 'NBH' => $NBH, 'NBF' => $NBF);
                $final[] = $stat;
            }

        }
        return $this->render('livre/parite.html.twig',array('list' => $final));
    }

    /**
     * Les 2 suivante pour la fonctionnalité 23
     */
    public function upBook(Livre $livre){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Livre::class);

        $livreActuel = $repo->find($livre->getId());

        $old = $livreActuel->getNote();
        if ($old+1 <= 20){
            
            $livreActuel->setNote($old+1);

            $em->persist($livreActuel);
            $em->flush();
        }

        return $this->render('livre/show.html.twig', [
            'livre' => $livre, 'auteurs' => $livre->getAuteur(), 'genres' => $livre->getGenre()
        ]);

    }

    public function downBook(Livre $livre){

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Livre::class);

        $livreActuel = $repo->find($livre->getId());

        $old = $livreActuel->getNote();
        if ($old-1 >= 0){
            
            $livreActuel->setNote($old-1);

            $em->persist($livreActuel);
            $em->flush();
        }

        return $this->render('livre/show.html.twig', [
            'livre' => $livre, 'auteurs' => $livre->getAuteur(), 'genres' => $livre->getGenre()
        ]);

    }


}
