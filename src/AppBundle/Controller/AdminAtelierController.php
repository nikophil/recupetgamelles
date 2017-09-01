<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Atelier;
use AppBundle\Entity\AtelierCategory;
use AppBundle\Form\AdminAtelierCategoryType;
use AppBundle\Form\AdminAtelierType;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminAtelierController
 * @Route("/admin/ateliers")
 */
class AdminAtelierController extends Controller
{
    /**
     * @Route("/", name="admin_ateliers")
     * @return Response
     */
    public function indexAction()
    {
        $ateliers = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Atelier')
            ->findAll();

        $atelierCategories = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:AtelierCategory')
            ->findBy([], ['position' => 'ASC']);

        $atelierAllRecipies = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'atelier-all']);

        return $this->render("admin/ateliers.html.twig", [
            'ateliers'          => $ateliers,
            'atelierCategories'     => $atelierCategories,
            'atelierAllRecipies'     => $atelierAllRecipies
        ]);
    }

    /**
     * @Route("/new", name="admin_atelier_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function atelierNewAction(Request $request)
    {
        $atelier = new Atelier();
        $form    = $this->createForm(AdminAtelierType::class, $atelier, [
            'validation_groups' => ['Default', 'create']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($atelier->getPdf()) {
                /** @var UploadedFile $file */
                $file     = $atelier->getPdf();
                $slugify  = new Slugify();
                $fileName = $slugify->slugify($atelier->getName()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('ateliers_directory'), $fileName);
                $atelier->setPdf($fileName);
            }

            $this->getDoctrine()->getManager()->persist($atelier);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'atelier a bien été créé');

            return $this->redirect($this->generateUrl('admin_ateliers'));
        }

        return $this->render("admin/form_atelier.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/edit/{id}", name="admin_atelier_edit")
     * @param Request $request
     * @param Atelier $atelier
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function atelierEditAction(Request $request, Atelier $atelier)
    {
        $oldpdf = $atelier->getPdf();
        $atelier->setPdf(
            new File($this->getParameter('ateliers_directory') . '/' . $atelier->getPdf())
        );

        $form = $this->createForm(AdminAtelierType::class, $atelier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $atelier->getPdf();
            if ($file !== null) {
                $slugify  = new Slugify();
                $fileName = $slugify->slugify($atelier->getName()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('ateliers_directory'), $fileName);
                $atelier->setPdf($fileName);
            } else {
                $atelier->setPdf($oldpdf);
            }

            $this->getDoctrine()->getManager()->persist($atelier);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'atelier a bien été modifié');

            return $this->redirect($this->generateUrl('admin_ateliers'));
        }

        return $this->render("admin/form_atelier.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_atelier_delete")
     * @param Atelier $atelier
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function atelierDelete(Atelier $atelier)
    {
        $this->getDoctrine()->getManager()->remove($atelier);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'L\'atelier a bien été supprimé');

        return $this->redirect($this->generateUrl('admin_ateliers'));
    }

    /**
     * @Route("/category/new", name="admin_atelier_category_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function categoryNewAction(Request $request)
    {
        $category = new AtelierCategory();
        $form     = $this->createForm(AdminAtelierCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $category->setIdentifier($slugify->slugify($category->getName()));
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bien été créée');

            return $this->redirect($this->generateUrl('admin_ateliers'));
        }

        return $this->render("admin/form_atelier_category.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/edit/{id}", name="admin_atelier_category_edit")
     * @param Request $request
     * @param AtelierCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function categoryEditAction(Request $request, AtelierCategory $category)
    {
        $form = $this->createForm(AdminAtelierCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée');

            return $this->redirect($this->generateUrl('admin_ateliers'));
        }

        return $this->render("admin/form_atelier_category.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/delete/{id}", name="admin_atelier_category_delete")
     * @param AtelierCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function categoryDelete(AtelierCategory $category)
    {
        $this->getDoctrine()->getManager()->remove($category);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée');

        return $this->redirect($this->generateUrl('admin_ateliers'));
    }

    /**
     * @Route("/update/pdf-all", name="admin_atelier_upload_all_ateliers")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updatePdfAllAteliersAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('atelier-all');

        if (null !== $file) {
            if (!in_array($file->getMimeType(), ['application/pdf', 'application/x-pdf'])) {
                $this->addFlash('error', 'Mauvais format de fichier sélectionné (pdf)');

                return $this->redirectToRoute('admin_ateliers');
            }

            $file->move($this->getParameter('kernel.root_dir') . '/../www/upload/', 'AnimationsRecupetGamelles.pdf');

            $this->addFlash('success', 'Fichier de tous les ateliers correctement mise à jour');

            return $this->redirectToRoute('admin_ateliers');
        }

        $this->addFlash('error', 'Aucun fichier sélectionné');

        return $this->redirectToRoute('admin_ateliers');
    }
}
