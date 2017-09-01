<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItemLeft;
use AppBundle\Form\AdminHomeType;
use AppBundle\Form\ContactModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @Route("/admin/home")
 */
class AdminHomeController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $homeContent = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'home']);

        $homeForm = $this->createForm(AdminHomeType::class, ['content' => $homeContent->getText()]);

        $homeForm->handleRequest($request);
        if ($homeForm->isValid()) {
            $data = $homeForm->getData();
            $homeContent->setText($data['content']);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->addFlash('success', 'Le contenu de la home a bien été modifié');

            return $this->redirect($this->generateUrl('admin_home'));
        }

        return $this->render("admin/home.html.twig", [
            'form' => $homeForm->createView()
        ]);
    }
}
