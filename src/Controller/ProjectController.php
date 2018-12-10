<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 03/12/2018
 * Time: 11:26
 */

namespace App\Controller;


use App\Entity\Project;
use App\Form\ProjectType;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectController extends AbstractController
{
    public function home():Response   {
        $myVar = 42;
        $url = $this->generateUrl( 'app_project_projdetail', ['noproj'=>143]);
        // var_dump ($url); die ('test generateUrl');

        // recup du repository des project
        $projrep = $this->getDoctrine()->getRepository(Project::class);
        // recup des projets
        $projs = $projrep->findAll();


        return $this->render('index.html.twig', [ 'projects'=>$projs]);
    }


    /** projActifs la liste des seuls projets actifs
     * @Route("/actifs")
     *
     */
    public function projActifs():Response   {
        $myVar = 42;
        $url = $this->generateUrl( 'app_project_projdetail', ['noproj'=>143]);
        // var_dump ($url); die ('test generateUrl');

        // recup du repository des project
        $projrep = $this->getDoctrine()->getRepository(Project::class);
        // recup des projets
        // $projs = $projrep->findAll();
        $projs = $projrep->findBy(['is_Published' => true]);

        return $this->render('index.html.twig', [ 'projects'=>$projs]);
    }
    /**
     * @Route("/projet/detail")
     *
     */
    public function detaild1Projet(){
        return new Response( '<html><body><h1>special detail de projet</h1>detail d\'un projet</body></html>');
    }
    /**
     * @Route("/thanks")
     *
     */
    public function thanks() {
        return new Response( '<html><body><h1>special thanks</h1>Et encore merci à tous, pour tout</body></html>');
    }
    /**
     * @Route("/projet/{noproj}",requirements={"noproj"="\d+"})
     *
     */
    public function projdetail(int $noproj=1 ):Response  {
        $projrep = $this->getDoctrine()->getRepository(Project::class);
        // recup des projets
        $proj = $projrep->find($noproj);
        if (is_null( $proj)){
            $this->addFlash('error', "projet $noproj non trouvé , Jean-Claude");
            throw $this->createNotFoundException("Slug $slug non trouvé dans les projets, Jean-Luc");
            return $this->render('errors/error404.html.twig');// inutile, je pense
        }
        // recup langages
        //$langages = $proj->getLangages();
           // var_dump($langages); die ('langages');

        return $this->render('project-show.html.twig', [ 'projet'=>$proj
            //,'langages'=>$langages
        ]);
    }
    // showSlug : affichage du projet concernant un slug
    /**
     * @Route("/projslug/{slug}")
     *
     */
    public function showSlug(string $slug ):Response  {
        $projrep = $this->getDoctrine()->getRepository(Project::class);
        // recup des projets
        $proj = $projrep->findOneBy([ "slug" => $slug]);

        if (is_null ($proj)) {
            throw $this->createNotFoundException("Slug $slug non trouvé dans les projets, Jean-Luc");
             $this->addFlash('error', "Slug $slug non trouvé dans les projets, Jean-Luc");
            return $this->render('errors/error404.html.twig');
        }

        $this->addFlash('success', "Slug $slug trouvé en base, Jean-Luc");

        return $this->render('project-show.html.twig', [ 'projet'=>$proj]);
    }
    //@Entity( pour dire qu'on veut l'appel à la methode findOneWithLangages plutot qu'a la methode classique
    // showSlugNew : affichage du projet concernant un slug mais tout en automatique grace à ligne param
    /**
     * @Route("/projslugnew/{slug}")
     * @Entity("projet",expr="repository.findOneWithLangages(slug)")
     * @param Project $projet
     * @return Response
     */
    public function showSlugNew(Project $projet):Response  {
        $slug = $projet->getSlug();

  /*      $projperso = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findOneWithLangages($slug);
  */
        // dump ($projperso);        die();

        return $this->render('project-show.html.twig', compact ('projet'));
    }
    /**
     * @Route("/session")
     *
     */
    public function create (SessionInterface $session):Response     {
        $session->set('MonNom', 'Frederic DESSAIN');
        return $this->redirectToRoute('index');

        // var_dump($session); die ('test recup session');
    }

    /**
     * @Route("/depuissession")
     *
     */

    public function recupDepuisSession (SessionInterface $session):Response     {
        $nom = $session->get('MonNom');

        return new Response("Nom : $nom");

        // var_dump($session); die ('test recup session');
    }
    /**
     * @Route("/flash/create")
     *
     */
    public function createFlash(){
        $this->addFlash('success', 'projet correctement ajouté');
        return $this->redirectToRoute('index');
    }

/**
* @Route("/flash/recup")
*
*/
    public function recupFlash(Session $session){
        $msg = $session->getFlashBag()->get('success');
        return new Response('Message Flash : ' . implode ('|', $msg));
    }
    /**
     * @Route("/create/manuel")
     *
     */
    public function createManuel(): Response
    {
        // creation d'un projet
        $projet = new Project();
        // Remplissage du projet
        $projet->setName ('Freebox ONE');
        $projet->setDescription( 'Sortie de la freebox ONE, nouvelle generation de box commexion Internet à vil prix');
        $projet->setProgrammedAt(new \DateTime('now'));
        $projet->setImage( 'freeboxone.jpg');
        $projet->setUrl( 'https://www.free.fr/freebox/freebox-one');

        // var_dump ($projet); die ('Pour voir');


    // Etape 3 : Enregistrer l’instance en BDD. Pour cela on se sert de Doctrine :

        // Recup de doctrine
        $manager = $this->getDoctrine()->getManager();
        // prepa du sql
        $manager->persist( $projet );

        // Exe du sql
        $manager->flush();
        // Redirection vers l'accueil
        $this->addFlash('success', 'Projet inséré en base, Jean-Luc. Et encore merci');
        return $this->redirectToRoute('index');
    }

    /** modif_proj : modif d'un projet, la plus automatique possible
     * @Route("/projet/modif/{id}")
     * @param Request $request
     * @param Project $project
     * @return Response
     */
    public function modif_proj(Request $request, Project $project): Response
    { // Modif du projet
        if ($project )
            $em = $this->getDoctrine()->getManager();
        if (! $project || ! $em) {
            throw NotFoundHttpException ("Projet non trouvé");
        }
        $name = $project->getName() ." (".$project->getId().')';
        $form = $this->createForm(ProjectType:: class, $project );
        // Recup donnees _POSTS
        $form->handleRequest( $request);
        // Verif validité des donnes
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            // Et puis on insert
            $mngr = $this->getDoctrine()->getManager();
            $mngr->persist($project);
            $mngr->flush();
            $this->addFlash('success', 'projet ' . $project->getName() . ' mis à jour');
            // redirection
            return $this->redirectToRoute('index');
        }

        return $this->render( "Project/modifproj.html.twig", [
            'createForm' => $form->createView()
        ]);


    }
    /** del_proj : detruit un projet, la plus automatique possible
     * @Route("/projet/suppr/{id}")
     * @param Project $projet
     * @return Response
     */
    public function del_proj(Project $projet): Response
    { // Modif du projet
        if ($projet )
            $em = $this->getDoctrine()->getManager();
        if (! $projet || ! $em) {
            throw NotFoundHttpException ("Produit non trouvé");
        }
        $name = $projet->getName() ." (".$projet->getId().')';
        $em->remove($projet);
        $em->flush();

        $this->addFlash('warning', 'projet ' .  $name . ' correctement supprimé');
        return $this->redirectToRoute( "index");
    }

    /** new_proj : detruit un projet, la plus automatique possible
     * @Route("/projet/add")
     * @param Project $projet
     * @return Response
     */
    public function new_proj(Request $request): Response
    { // Création d'un nouveau produit
        $project = new Project();
        // creation
        $form = $this->createForm(ProjectType:: class, $project );

        // Recup donnees _POSTS
        $form->handleRequest( $request);
        // Verif validité des donnes
        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            // Et puis on insert
            $mngr = $this->getDoctrine()->getManager();
            $mngr->persist($project);
            $mngr->flush();
            $this->addFlash('success', 'projet ' . $project->getName() . ' créé');
            // redirection
            return $this->redirectToRoute('index');
        }

        return $this->render( "Project/create.html.twig", [
            'createForm' => $form->createView()
        ]);

    }
}