<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AgendaItemLeft;
use AppBundle\Form\AdminHomeType;
use AppBundle\Form\ContactModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AdminGourmetBagController
 * @Route("/admin/gourmet-bag")
 */
class AdminGourmetBagController extends Controller
{
    /**
     * @Route("/", name="admin_gourmet_bag")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $imageGourmetBagTop = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'gourmet-bag-image-top']);

        $imageGourmetBagBottom = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:RichText')
            ->findOneBy(['identifier' => 'gourmet-bag-image-bottom']);

        return $this->render("admin/gourmet_bag.html.twig", [
            'imageGourmetBagTop'    => $imageGourmetBagTop,
            'imageGourmetBagBottom' => $imageGourmetBagBottom,
        ]);
    }

    /**
     * @Route("/image-top", name="admin_gourmet_bag_image_top")
     * @param Request $request
     * @return Response
     */
    public function handleImageTopAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('image-gourmet-bag-top');

        if (null !== $file) {
            if (substr($file->getMimeType(), 0, 5) != 'image') {
                $this->addFlash('error', 'Mauvais format de fichier sélectionné (jpg ou png)');

                return $this->redirectToRoute('admin_gourmet_bag');
            }

            $imageSize = getimagesize($file->getFileInfo()->getRealPath());
            if ($imageSize[0] != 1385 || $imageSize[1] != 800) {
                $this->addFlash('error', 'Mauvaise taille du fichier sélectionné (1385x800)');

                return $this->redirectToRoute('admin_gourmet_bag');
            }

            $extension = $file->guessExtension();
            $file->move($this->getParameter('kernel.root_dir') . '/../www/images/gourmet_bag', 'top.' . $extension);

            $imageGourmetBagTop = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:RichText')
                ->findOneBy(['identifier' => 'gourmet-bag-image-top']);
            $imageGourmetBagTop->setText('top.' . $extension);
            $this->get('doctrine.orm.entity_manager')->persist($imageGourmetBagTop);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->addFlash('success', 'Image correctement mise à jour');

            return $this->redirectToRoute('admin_gourmet_bag');
        }

        $this->addFlash('error', 'Aucun fichier sélectionné');

        return $this->redirectToRoute('admin_gourmet_bag');
    }

    /**
     * @Route("/image-bottom", name="admin_gourmet_bag_image_bottom")
     * @param Request $request
     * @return Response
     */
    public function handleImageBottomAction(Request $request)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('image-gourmet-bag-bottom');

        if (null !== $file) {
            if (substr($file->getMimeType(), 0, 5) != 'image') {
                $this->addFlash('error', 'Mauvais format de fichier sélectionné (jpg ou png)');

                return $this->redirectToRoute('admin_gourmet_bag');
            }

            $imageSize = getimagesize($file->getFileInfo()->getRealPath());
            if ($imageSize[0] != 1386 || $imageSize[1] != 486) {
                $this->addFlash('error', 'Mauvaise taille du fichier sélectionné (1386x486)');

                return $this->redirectToRoute('admin_gourmet_bag');
            }

            $extension = $file->guessExtension();
            $file->move($this->getParameter('kernel.root_dir') . '/../www/images/gourmet_bag', 'bottom.' . $extension);

            $imageGourmetBagBottom = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:RichText')
                ->findOneBy(['identifier' => 'gourmet-bag-image-bottom']);
            $imageGourmetBagBottom->setText('bottom.' . $extension);
            $this->get('doctrine.orm.entity_manager')->persist($imageGourmetBagBottom);
            $this->get('doctrine.orm.entity_manager')->flush();

            $this->addFlash('success', 'Image correctement mise à jour');

            return $this->redirectToRoute('admin_gourmet_bag');
        }

        $this->addFlash('error', 'Aucun fichier sélectionné');

        return $this->redirectToRoute('admin_gourmet_bag');
    }
}
