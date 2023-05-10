<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/genre")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/", name="genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="genre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genre_show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Genre $genre): Response
    {
        $pages = 0;

        $genreFind = $this->getDoctrine()->getRepository(Genre::class)->find($genre->getId());
        $livres = $genreFind->getLivre();

        foreach ($livres as $l) {
            $pages += $l->getNbpages();
        }

        $N = 0;
        $total = 0;

        foreach ($livres as $l){
            $nbpage = $l->getNbpages();
            $total += $nbpage;
            $N++;
        }
        
        if ($N != 0)
            $moy = $total/$N;
        else
            $moy = 0;


        return $this->render('genre/show.html.twig', [
            'genre' => $genre, 'nbPages' => $pages, 'moy' =>$moy
        ]);
    }

    /**
     * @Route("/{id}/edit", name="genre_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="genre_delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, Genre $genre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($genre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('genre_index', [], Response::HTTP_SEE_OTHER);
    }

    public function showAuteur(Genre $genre){ // Fonction qui gère la fonctionnalité 18
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Genre::class);
        $genreFind = $repo->find($genre->getId());

        $listLivre = $genreFind->getLivre();
        $tab = array();

        foreach ($listLivre as $livre){
                
            $listAuteur = $livre->getAuteur();

            foreach ($listAuteur as $auteur){
                $tab[] = $auteur;
            }
        }

        $tab = array_unique($tab);


        return $this->render('genre/show-auteur.html.twig', [
            'genre' => $genre, 'auteurs' => $tab
        ]);
    }

    public function voidGenre() { // Fonction qui gère la fonctionnalité 24
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Genre::class);
        $genres = $repo->findAll();

        foreach ($genres as $genre) {
            $livres = $genre->getLivre();

            $n = 0;

            foreach ($livres as $livre) {
                $n += 1;
            }

            if ($n == 0){
                $em->remove($genre);
                $em->flush();
            }
        }

        $genres = $repo->findAll();

        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }
}
