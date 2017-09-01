<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Boolean;
use AppBundle\Entity\Recette;
use AppBundle\Entity\RecetteCategory;
use AppBundle\Entity\RichText;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadCategoryRecetteData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCategoryRecetteData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $this->container->get('doctrine.orm.entity_manager')->getConnection()->exec('DELETE FROM rg_recette_category');

        $category1 = (new RecetteCategory())
            ->setName('Avec du pain sec')
            ->setIdentifier('category-pain')
            ->setOrder(0)
            ->setPosition('left');
        $this->setReference('category-pain', $category1);
        $manager->persist($category1);

        $category2 = (new RecetteCategory())
            ->setName('Avec des fanes')
            ->setIdentifier('category-fanes')
            ->setOrder(1)
            ->setPosition('left');
        $this->setReference('category-fanes', $category2);
        $manager->persist($category2);

        $category3 = (new RecetteCategory())
            ->setName('Avec des légumes entiers')
            ->setIdentifier('category-legumes')
            ->setOrder(2)
            ->setPosition('left');
        $this->setReference('category-legumes', $category3);
        $manager->persist($category3);

        $category4 = (new RecetteCategory())
            ->setName('Avec des des fruits et légumes défraichis')
            ->setIdentifier('category-fruits')
            ->setOrder(3)
            ->setPosition('right');
        $this->setReference('category-fruits', $category4);
        $manager->persist($category4);

        $category5 = (new RecetteCategory())
            ->setName('Avec du chocolat cassé, blanchi ou en DDM dépassée')
            ->setIdentifier('category-autre')
            ->setOrder(4)
            ->setPosition('right');
        $this->setReference('category-autre', $category5);
        $manager->persist($category5);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}