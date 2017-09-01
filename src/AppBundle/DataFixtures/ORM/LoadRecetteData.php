<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Boolean;
use AppBundle\Entity\Recette;
use AppBundle\Entity\RichText;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadRecetteData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadRecetteData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->container->get('doctrine.orm.entity_manager')->getConnection()->exec('DELETE FROM rg_recette');

        $this->createRecette($manager, 'Cakes', 'category-pain', 'Cake.pdf', 0);
        $this->createRecette($manager, 'Cookies', 'category-pain', 'Cookies.pdf', 1);
        $this->createRecette($manager, 'Gaufres façon pain perdu', 'category-pain', 'Gaufres.pdf', 2);
        $this->createRecette($manager, 'Pâte à pizza', 'category-pain', 'PateAPizza.pdf', 3);
        $this->createRecette($manager, 'Pâte à tarte', 'category-pain', 'PateAtarte.pdf', 4);
        $this->createRecette($manager, 'Chapelure', 'category-pain', 'Chapelure.pdf', 5);

        $this->createRecette($manager, 'Pesto aux fanes de carottes', 'category-fanes', 'PestoFanes.pdf', 0);

        $this->createRecette($manager, 'Tagliatelles de légumes crus', 'category-legumes', 'TagliatellesLegumesCrus.pdf', 0);

        $this->createRecette($manager, 'Caviar d\'aubergines', 'category-fruits', 'CaviarAubergine.pdf', 0);
        $this->createRecette($manager, 'Cocktail chaud<br>Pomme-cannelle', 'category-fruits', 'CocktailPomCannelle.pdf', 1);
        $this->createRecette($manager, 'Jus de fruits', 'category-fruits', 'JusDeFruits.pdf', 2);
        $this->createRecette($manager, 'Smoothies', 'category-fruits', 'SmoothiesFruitsAbimes.pdf', 3);
        $this->createRecette($manager, 'Lassi', 'category-fruits', 'Lassi.pdf', 4);
        $this->createRecette($manager, 'Velouté de laitue', 'category-fruits', 'VelouteDeLaitue.pdf', 5);
        $this->createRecette($manager, 'Sauce Aigre-douce', 'category-fruits', 'SauceAigreDouce.pdf', 6);

        $this->createRecette($manager, 'Pâte à tartiner maison', 'category-autre', 'PateATartiner.pdf', 0);

        $manager->flush();
    }

    private function createRecette(ObjectManager $manager, $nom, $category, $pdf, $position)
    {
        $recette = (new Recette())
            ->setName($nom)
            ->setCategory($this->getReference($category))
            ->setPdf($pdf)
            ->setPosition($position)
            ->setActive(true)
        ;
        $manager->persist($recette);
    }

    public function getOrder()
    {
        return 4;
    }
}