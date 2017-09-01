<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItemLeft;
use AppBundle\Entity\Recette;
use AppBundle\Entity\RecetteCategory;
use AppBundle\Form\AdminHomeType;
use AppBundle\Form\AdminRecetteCategoryType;
use AppBundle\Form\AdminRecetteType;
use AppBundle\Form\ContactModel;
use Cocur\Slugify\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminRecettesController
 * @Route("/admin/recettes")
 */
class AdminRecettesController extends Controller
{
    /**
     * @Route("/", name="admin_recettes")
     * @return Response
     */
    public function indexAction()
    {
        $recettes = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Recette')
            ->findAll();

        $recetteCategories = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RecetteCategory')
            ->findBy([], ['position' => 'ASC']);

        $atelierActive = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Boolean')
            ->findOneBy(['identifier' => 'atelier-active']);

        $imageAteliers = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'image-atelier']);

        return $this->render("admin/recettes.html.twig", [
            'recettes'          => $recettes,
            'atelierActive'     => $atelierActive,
            'recetteCategories' => $recetteCategories,
            'imageAteliers'     => $imageAteliers,
        ]);
    }

    /**
     * @Route("/upload-image", name="admin_recettes_handle_atelier")
     * @param Request $request
     * @return Response
     */
    public function handleAtelierAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('image-atelier');

        if (null !== $file) {
            if (substr($file->getMimeType(), 0, 5) != 'image') {
                $this->addFlash('error', 'Mauvais format de fichier sélectionné (jpg ou png)');

                return $this->redirectToRoute('admin_recettes');
            }

            $imageSize = getimagesize($file->getFileInfo()->getRealPath());
            if ($imageSize[0] != 493 || $imageSize[1] != 684) {
                $this->addFlash('error', 'Mauvaise taille du fichier sélectionné (493x684)');

                return $this->redirectToRoute('admin_recettes');
            }

            $extension = $file->guessExtension();
            $file->move($this->getParameter('kernel.root_dir') . '/../www/images/recettes', 'ateliers-boco.' . $extension);

            $imageAteliers = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:RichText')
                ->findOneBy(['identifier' => 'gourmet-bag-image-top']);
            $imageAteliers->setText('ateliers-boco.' . $extension);
            $this->get('doctrine.orm.entity_manager')->persist($imageAteliers);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->addFlash('success', 'Image correctement mise à jour');
        }

        $atelierActive = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Boolean')
            ->findOneBy(['identifier' => 'atelier-active']);
        if ($request->request->has('active-atelier')) {
            $atelierActive->setBool(true);
            $this->addFlash('success', 'Atelier correctement activé');
        } else {
            $atelierActive->setBool(false);
            $this->addFlash('success', 'Atelier correctement désactivé');
        }
        $this->get('doctrine.orm.entity_manager')->persist($atelierActive);
        $this->get('doctrine.orm.entity_manager')->flush();

        return $this->redirectToRoute('admin_recettes');

    }

    /**
     * @Route("/new", name="admin_recette_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function recetteNewAction(Request $request)
    {
        $recette = new Recette();
        $form    = $this->createForm(AdminRecetteType::class, $recette, [
            'validation_groups' => ['Default', 'create']
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file     = $recette->getPdf();
            $slugify  = new Slugify();
            $fileName = $slugify->slugify($recette->getName()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('recettes_directory'), $fileName);
            $recette->setPdf($fileName);

            $this->getDoctrine()->getManager()->persist($recette);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La recette a bien été créée');

            return $this->redirect($this->generateUrl('admin_recettes'));
        }

        return $this->render("admin/form_recette.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/edit/{id}", name="admin_recette_edit")
     * @param Request $request
     * @param Recette $recette
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function recetteEditAction(Request $request, Recette $recette)
    {
        $oldpdf = $recette->getPdf();
        $recette->setPdf(
            new File($this->getParameter('recettes_directory') . '/' . $recette->getPdf())
        );

        $form = $this->createForm(AdminRecetteType::class, $recette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $recette->getPdf();
            if ($file !== null) {
                $slugify  = new Slugify();
                $fileName = $slugify->slugify($recette->getName()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('recettes_directory'), $fileName);
                $recette->setPdf($fileName);
            } else {
                $recette->setPdf($oldpdf);
            }

            $this->getDoctrine()->getManager()->persist($recette);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La recette a bien été modifiée');

            return $this->redirect($this->generateUrl('admin_recettes'));
        }

        return $this->render("admin/form_recette.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="admin_recette_delete")
     * @param Recette $recette
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function recetteDelete(Recette $recette)
    {
        $this->getDoctrine()->getManager()->remove($recette);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'La recette a bien été supprimée');

        return $this->redirect($this->generateUrl('admin_recettes'));
    }

    /**
     * @Route("/category/new", name="admin_recette_category_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function categoryNewAction(Request $request)
    {
        $category = new RecetteCategory();
        $form     = $this->createForm(AdminRecetteCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $category->setIdentifier($slugify->slugify($category->getName()));
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bien été créée');

            return $this->redirect($this->generateUrl('admin_recettes'));
        }

        return $this->render("admin/form_recette_category.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/edit/{id}", name="admin_recette_category_edit")
     * @param Request $request
     * @param RecetteCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function categoryEditAction(Request $request, RecetteCategory $category)
    {
        $form = $this->createForm(AdminRecetteCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($category);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée');

            return $this->redirect($this->generateUrl('admin_recettes'));
        }

        return $this->render("admin/form_recette_category.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/category/delete/{id}", name="admin_recette_category_delete")
     * @param RecetteCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function categoryDelete(RecetteCategory $category)
    {
        $this->getDoctrine()->getManager()->remove($category);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée');

        return $this->redirect($this->generateUrl('admin_recettes'));
    }
}
