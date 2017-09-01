<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Boolean;
use AppBundle\Entity\RichText;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadContactThemeData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadBoolData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $this->container->get('doctrine.orm.entity_manager')->getConnection()->exec('DELETE FROM rg_bool');

        $richTextHome = (new Boolean())
            ->setIdentifier('atelier-active')
            ->setBool(true)
        ;
        $manager->persist($richTextHome);


        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}