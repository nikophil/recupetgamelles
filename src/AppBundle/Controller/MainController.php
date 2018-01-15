<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactModel;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $homeContent = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'home']);

        return $this->render('index.html.twig', [
            'homeText' => $homeContent->getText()
        ]);
    }

    /**
     * @Route("/agenda", name="agenda")
     */
    public function agendaAction(Request $request)
    {
        $agendasLeft = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:AgendaItemLeft')
            ->findBy(array('active' => true), array('date' => 'asc'));

        $agendasRight = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:AgendaItemRight')
            ->findBy(array('active' => true), array('date' => 'asc'));

        return $this->render('agenda.html.twig', array(
            'agendasLeft'  => $agendasLeft,
            'agendasRight' => $agendasRight,
        ));
    }

    /**
     * @Route("/ateliers/", name="ateliers")
     */
    public function ateliersAction(Request $request)
    {
        $categoriesWithAteliers = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:AtelierCategory')
            ->findAll();

        $leftCategoriesWithAteliers  = [];
        $rightCategoriesWithAteliers = [];
        foreach ($categoriesWithAteliers as $category) {
            if ($category->getPosition() == 'left') {
                $leftCategoriesWithAteliers[] = $category;
            } else {
                $rightCategoriesWithAteliers[] = $category;
            }
        }

        return $this->render('ateliers.html.twig', [
            'leftCategoriesWithAteliers'  => $leftCategoriesWithAteliers,
            'rightCategoriesWithAteliers' => $rightCategoriesWithAteliers
        ]);
    }

    /**
     * @Route("/recettes/", name="recettes")
     * @Route("/recettes/complet", name="recettes_complet")
     */
    public function recettesAction(Request $request)
    {
        $atelierActive = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Boolean')
            ->findOneBy(['identifier' => 'atelier-active']);

        $categoriesWithRecettes = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RecetteCategory')
            ->findAll();

        $leftCategoriesWithRecettes  = [];
        $rightCategoriesWithRecettes = [];
        foreach ($categoriesWithRecettes as $category) {
            if ($category->getPosition() == 'left') {
                $leftCategoriesWithRecettes[] = $category;
            } else {
                $rightCategoriesWithRecettes[] = $category;
            }
        }

        return $this->render('recettes.html.twig', [
            'atelierActive'               => $atelierActive,
            'leftCategoriesWithRecettes'  => $leftCategoriesWithRecettes,
            'rightCategoriesWithRecettes' => $rightCategoriesWithRecettes
        ]);
    }

    /**
     * @Route("/benevolat/{responsePayment}", name="benevolat")
     */
    public function benevolatAction(Request $request, $responsePayment = null)
    {
        return $this->render('benevolat.html.twig', array(
            'responsePayment' => $responsePayment
        ));
    }

    /**
     * @Route("/bocalerie", name="conserverie")
     */
    public function conserverieAction(Request $request)
    {
        return $this->render('conserverie_solidaire.html.twig');
    }

//    /**
//     * @Route("/gourmet-bag", name="gourmet-bag")
//     */
//    public function gourmetBagAction(Request $request)
//    {
//        $imageGourmetBagTop = $this->get('doctrine.orm.entity_manager')
//            ->getRepository('AppBundle:RichText')
//            ->findOneBy(['identifier' => 'gourmet-bag-image-top']);
//
//        $imageGourmetBagBottom = $this->get('doctrine.orm.entity_manager')
//            ->getRepository('AppBundle:RichText')
//            ->findOneBy(['identifier' => 'gourmet-bag-image-bottom']);
//
//        return $this->render('gourmet_bag.html.twig', [
//            'imageGourmetBagTop'    => $imageGourmetBagTop,
//            'imageGourmetBagBottom' => $imageGourmetBagBottom,
//        ]);
//    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $contact = new ContactModel();
        $form    = $this->createForm('AppBundle\Form\ContactType', $contact);

        $form->handleRequest($request);

        $failed = array();

        if ($form->isSubmitted() && $form->isValid()) {
            $mailer = $this->get('swiftmailer.mailer');

            $body    = sprintf('
Une nouvelle demande de contact est arrivée :<br><br>
<b>Nom : </b> %s<br>
<b>Email : </b> %s<br>
<b>Sujet : </b> %s<br>
<b>Message : </b><br>%s<br>
', $contact->getName(), $contact->getEmail(), $contact->getSubject(), nl2br($contact->getMessage()));
            $message = \Swift_Message::newInstance()
                ->setSubject('Nouvelle demande de contact via le site internet')
                ->setFrom('no-reply@recupetgamelles.fr')
                ->setTo('contact@recupetgamelles.fr')
                ->setContentType('text/html')
                ->setBody($body);

            $mailer->send($message, $failed);

            $this->addFlash('success', 'Votre message a été correctement envoyé');

            $this->redirect($this->generateUrl('contact'));
        }

        return $this->render('infos_contacts.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/invendus", name="invendus")
     */
    public function invendusAction(Request $request)
    {
        return $this->render('invendus.html.twig');
    }

    /**
     * @Route("/l-association", name="association")
     */
    public function associationAction(Request $request)
    {
        $associationContent = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'association']);
        return $this->render('association.html.twig', [
            'associationText' => $associationContent
        ]);
    }

    /**
     * @Route("/accompagnement-0-gaspi-0-dechet", name="accompagnement")
     */
    public function projectsAction()
    {
        return $this->render('accompagnement.html.twig');
    }
}
