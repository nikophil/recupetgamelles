<?php

namespace AppBundle\Controller;

use AppBundle\Form\AdminAssociationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminAssociationController
 * @Route("/admin/association")
 */
class AdminAssociationController extends Controller
{
    /**
     * @Route("/", name="admin_asso")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $associationContent = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'association']);

        $associationForm = $this->createForm(AdminAssociationType::class, ['content' => $associationContent->getText()]);

        $associationForm->handleRequest($request);
        if ($associationForm->isValid()) {
            $data = $associationForm->getData();
            $associationContent->setText($data['content']);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->addFlash('success', 'Le contenu de la page "association" a bien été modifié');

            return $this->redirect($this->generateUrl('admin_asso'));
        }

        return $this->render("admin/association.html.twig", [
            'form' => $associationForm->createView()
        ]);
    }
}
