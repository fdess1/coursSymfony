<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 06/12/2018
 * Time: 12:23
 */

namespace App\Controller;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Langages;
use App\Form\LangagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/langage")
 * @package App\Controller
 */
class LangageController extends AbstractController {
    /**
     * @Route("/creation")
     */
    public function creelangage(Request $request): Response
    {
        $langage = new Langages();
        // creation
        $form = $this->createForm(LangagesType::class, $langage);

        // Recup donnees _POSTS
         $form->handleRequest($request);
        // Verif validité des donnes

      /*  $this->addFlash('success', "isSubmitted ".
            ($form->isSubmitted()?"OK":"NON OK").
            ($form->isSubmitted()?($form->isValid()?'is-valid OK':'is-valid NON OK')
                :''));*/

        if ($form->isSubmitted() && $form->isValid()) {
            $langage = $form->getData();
            // Et puis on insert
            $mngr = $this->getDoctrine()->getManager();
            $mngr->persist($langage);
            $mngr->flush();

            $this->addFlash('success', 'langage ' . $langage->getLgNom() . ' créé');
            // redirection
            return $this->redirectToRoute('app_langage_listelang');
        }
        //else {   if ($form->isSubmitted()) {      var_dump($form);    die ('formulaire invalide');  } }

        return $this->render( "Langages/create.html.twig", [
            'createForm' => $form->createView()
        ]);

    }
    /** La liste des langages référencés
     * @Route("/liste")
     */
    public function listeLang (): Response {
        // var_dump ($url); die ('test generateUrl');

        // recup du repository des project
    $projrep = $this->getDoctrine()->getRepository(Langages::class);
        // recup des projet
    $lgges = $projrep->findAll();

    return $this->render('Langages/listelgg.html.twig', [ 'lgges'=>$lgges]);
    }
}