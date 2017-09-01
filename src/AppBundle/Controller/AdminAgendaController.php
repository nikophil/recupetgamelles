<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItemLeft;
use AppBundle\Entity\AgendaItemRight;
use AppBundle\Form\AdminAgendaItemLeftType;
use AppBundle\Form\AdminAgendaItemRightType;
use AppBundle\Form\ContactModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @Route("/admin/agenda")
 */
class AdminAgendaController extends Controller
{
    /**
     * @Route("/", name="admin_agenda")
     */
    public function indexAction(Request $request)
    {
        $agendasLeft = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:AgendaItemLeft')
            ->findBy(array(), array('date' => 'desc'));

        $agendasRight = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:AgendaItemRight')
            ->findBy(array(), array('date' => 'desc'));

        return $this->render('admin/agenda.html.twig', array(
            'agendasLeft' => $agendasLeft,
            'agendasRight' => $agendasRight,
        ));
    }

    /**
     * @Route("/agenda/left/new", name="admin_agenda_left_new")
     */
    public function agendaLeftNewAction(Request $request)
    {
        $agenda = new AgendaItemLeft();
        $form = $this->createForm(AdminAgendaItemLeftType::class, $agenda);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'entrée de l\'agenda a bien été créée');

            return $this->redirect($this->generateUrl('admin_agenda'));
        }

        return $this->render("admin/form_agenda.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/agenda/left/edit/{id}", name="admin_agenda_left_edit")
     * @param Request $request
     * @param AgendaItemLeft $agenda
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function agendaLeftEditAction(Request $request, AgendaItemLeft $agenda)
    {
        $form = $this->createForm(AdminAgendaItemLeftType::class, $agenda);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'entrée de l\'agenda a bien été modifiée');

            return $this->redirect($this->generateUrl('admin_agenda'));
        }

        return $this->render("admin/form_agenda.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/agenda/left/delete/{id}", name="admin_agenda_left_delete")
     * @param AgendaItemLeft $agenda
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function agendaLeftDelete(AgendaItemLeft $agenda)
    {
        $this->getDoctrine()->getManager()->remove($agenda);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'L\'entrée de l\'agenda a bien été supprimée');

        return $this->redirect($this->generateUrl('admin_agenda'));
    }

    /**
     * @Route("/agenda/right/new", name="admin_agenda_right_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function agendaRightNewAction(Request $request)
    {
        $agenda = new AgendaItemRight();
        $form = $this->createForm(AdminAgendaItemRightType::class, $agenda);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'entrée de l\'agenda a bien été créée');

            return $this->redirect($this->generateUrl('admin_agenda'));
        }

        return $this->render("admin/form_agenda.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/agenda/right/edit/{id}", name="admin_agenda_right_edit")
     * @param Request $request
     * @param AgendaItemRight $agenda
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function agendaRightEditAction(Request $request, AgendaItemRight $agenda)
    {
        $form = $this->createForm(AdminAgendaItemRightType::class, $agenda);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'entrée de l\'agenda a bien été modifiée');

            return $this->redirect($this->generateUrl('admin_agenda'));
        }

        return $this->render("admin/form_agenda.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/agenda/right/delete/{id}", name="admin_agenda_right_delete")
     * @param AgendaItemRight $agenda
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function agendaRightDelete(AgendaItemRight $agenda)
    {
        $this->getDoctrine()->getManager()->remove($agenda);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'L\'entrée de l\'agenda a bien été supprimée');

        return $this->redirect($this->generateUrl('admin_agenda'));
    }
}
