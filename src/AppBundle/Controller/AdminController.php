<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItemLeft;
use AppBundle\Form\ContactModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminController
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function indexAction(Request $request)
    {
        return $this->render("admin/index.html.twig");
    }
}
