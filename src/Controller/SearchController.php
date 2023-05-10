<?php

namespace App\Controller;

use App\Form\SearchLivreType;
use App\Form\AdvancedSearchLivreType;
use App\Form\ApdateAllBookNote;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SearchController extends AbstractController{

    /**
     * @Route("/livre/search", name="search_livre")
     */
    public function searchBook(Request $request, LivreRepository $liveRepository){
        $livre = [];

        $searchLivreForm = $this->createForm(SearchLivreType::class);
        $advancedSearchForm = $this->createForm(AdvancedSearchLivreType::class);

        if ($searchLivreForm->handleRequest($request)->isSubmitted() && $searchLivreForm->isValid()) {
            $valeur = $searchLivreForm->getData();
            $livre = $liveRepository->searchName($valeur);
        }

        if ($advancedSearchForm->handleRequest($request)->isSubmitted() && $advancedSearchForm->isValid()) {
            $valeur = $advancedSearchForm->getData();
            $livre = $liveRepository->searchBook($valeur);
        }

        return $this->render('search/livre.html.twig', [
            'search_livre' => $searchLivreForm->createView(),
            'livre' => $livre,
            'search_form' => $advancedSearchForm->createView()
        ]);
    }
}