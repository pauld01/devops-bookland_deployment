<?php

namespace App\Controller;


use App\Entity\Genre;
use App\Repository\GenreRepository;
use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Render;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository,Session $session): Response
    {
	    if ($session->has('nbreFois'))
	        $session->set('nbreFois', $session->get('nbreFois')+1);
	    else
	        $session->set('nbreFois', 1);

        return $this->render('index.html.twig', array('livres5' => $livreRepository->best(),'livresLast' => $livreRepository->last(),
	        'nbreFois' => $session->get('nbreFois')
        ));
    }

    /**
     * @Route("/bookland/init", name="init", methods={"GET"})
     */
    public function init()
    {
        $em = $this->getDoctrine()->getManager();

        $Auteur1 = new Auteur();
        $Auteur1->setNomPrenom('Richard Thaler');
        $Auteur1->setSexe('M');
        $date = date_create("1945-12-12");
        $Auteur1->setDateDeNaissance($date);
        $Auteur1->setNationalite('US');
        $em->persist($Auteur1);

        $Auteur2 = new Auteur();
        $Auteur2->setNomPrenom('Cass Sunstein');
        $Auteur2->setSexe('M');
        $date = date_create("1943-11-23");
        $Auteur2->setDateDeNaissance($date);
        $Auteur2->setNationalite('DE');
        $em->persist($Auteur2);

        $Auteur3 = new Auteur();
        $Auteur3->setNomPrenom('Francis Gabrelot');
        $Auteur3->setSexe('M');
        $date = date_create("1967-01-29");
        $Auteur3->setDateDeNaissance($date);
        $Auteur3->setNationalite('FR');
        $em->persist($Auteur3);

        $Auteur4 = new Auteur();
        $Auteur4->setNomPrenom('Ayn Rand');
        $Auteur4->setSexe('F');
        $date = date_create("1950-06-21");
        $Auteur4->setDateDeNaissance($date);
        $Auteur4->setNationalite('RU');
        $em->persist($Auteur4);

        $Auteur5 = new Auteur();
        $Auteur5->setNomPrenom('Duschmol');
        $Auteur5->setSexe('M');
        $date = date_create("2001-12-23");
        $Auteur5->setDateDeNaissance($date);
        $Auteur5->setNationalite('GL');
        $em->persist($Auteur5);

        $Auteur6 = new Auteur();
        $Auteur6->setNomPrenom('Nancy Grave');
        $Auteur6->setSexe('F');
        $date = date_create("1952-10-24");
        $Auteur6->setDateDeNaissance($date);
        $Auteur6->setNationalite('US');
        $em->persist($Auteur6);

        $Auteur7 = new Auteur();
        $Auteur7->setNomPrenom('James Enckling');
        $Auteur7->setSexe('M');
        $date = date_create("1970-07-03");
        $Auteur7->setDateDeNaissance($date);
        $Auteur7->setNationalite('US');
        $em->persist($Auteur7);

        $Auteur8 = new Auteur();
        $Auteur8->setNomPrenom('Jean Dupont');
        $Auteur8->setSexe('M');
        $date = date_create("1970-07-03");
        $Auteur8->setDateDeNaissance($date);
        $Auteur8->setNationalite('FR');
        $em->persist($Auteur8);

        $Genre1 = new Genre();
        $Genre1->setNom('science fiction');
        $em->persist($Genre1);

        $Genre2 = new Genre();
        $Genre2->setNom('policier');
        $em->persist($Genre2);

        $Genre3 = new Genre();
        $Genre3->setNom('philosophie');
        $em->persist($Genre3);

        $Genre4 = new Genre();
        $Genre4->setNom('économie');
        $em->persist($Genre4);

        $Genre5 = new Genre();
        $Genre5->setNom('psychologie');
        $em->persist($Genre5);

        $Livre1 = new Livre();
        $Livre1->setTitre('Symfonystique');
        $Livre1->setIsbn('978-2-07-036822-8');
        $Livre1->setNbpages(117);
        $date = date_create("2008-01-20");
        $Livre1->setDateDeParution($date);
        $Livre1->setNote(8);
        $Livre1->addGenre($Genre2);
        $Livre1->addGenre($Genre3);
        $Livre1->addAuteur($Auteur3);
        $Livre1->addAuteur($Auteur4);
        $Livre1->addAuteur($Auteur6);
        $em->persist($Livre1);

        $Livre2 = new Livre();
        $Livre2->setTitre('La grève');
        $Livre2->setIsbn('978-2-251-44417-8');
        $Livre2->setNbpages(1245);
        $date = date_create("1961-06-12");
        $Livre2->setDateDeParution($date);
        $Livre2->setNote(19);
        $Livre2->addGenre($Genre3);
        $Livre2->addAuteur($Auteur4);
        $Livre2->addAuteur($Auteur7);
        $em->persist($Livre2);

        $Livre3 = new Livre();
        $Livre3->setTitre('Symfonyland');
        $Livre3->setIsbn('978-2-212-55652-0');
        $Livre3->setNbpages(131);
        $date = date_create("1980-09-17");
        $Livre3->setDateDeParution($date);
        $Livre3->setNote(15);
        $Livre3->addGenre($Genre1);
        $Livre3->addAuteur($Auteur8);
        $Livre3->addAuteur($Auteur7);
        $Livre3->addAuteur($Auteur4);
        $em->persist($Livre3);

        $Livre4 = new Livre();
        $Livre4->setTitre('Négociation Complexe');
        $Livre4->setIsbn('978-2-0807-1057-4');
        $Livre4->setNbpages(234);
        $date = date_create("1992-09-25");
        $Livre4->setDateDeParution($date);
        $Livre4->setNote(16);
        $Livre4->addGenre($Genre5);
        $Livre4->addAuteur($Auteur1);
        $Livre4->addAuteur($Auteur2);
        $em->persist($Livre4);

        $Livre5 = new Livre();
        $Livre5->setTitre('Ma vie');
        $Livre5->setIsbn('978-0-300-12223-7');
        $Livre5->setNbpages(5);
        $date = date_create("2021-11-08");
        $Livre5->setDateDeParution($date);
        $Livre5->setNote(3);
        $Livre5->addGenre($Genre2);
        $Livre5->addAuteur($Auteur8);
        $em->persist($Livre5);

        $Livre6 = new Livre();
        $Livre6->setTitre('Ma vie : suite');
        $Livre6->setIsbn('978-0-141-18776-1');
        $Livre6->setNbpages(5);
        $date = date_create("2021-11-09");
        $Livre6->setDateDeParution($date);
        $Livre6->setNote(1);
        $Livre6->addGenre($Genre2);
        $Livre6->addAuteur($Auteur8);
        $em->persist($Livre6);

        $Livre7 = new Livre();
        $Livre7->setTitre('Le monde comme volonté et comme représentation');
        $Livre7->setIsbn('978-0-141-18786-0');
        $Livre7->setNbpages(1987);
        $date = date_create("1821-11-09");
        $Livre7->setDateDeParution($date);
        $Livre7->setNote(19);
        $Livre7->addGenre($Genre3);
        $Livre7->addAuteur($Auteur6);
        $Livre7->addAuteur($Auteur3);
        $em->persist($Livre7);

        $em->flush();

        return $this->render('init.html.twig');
    }
	
}
