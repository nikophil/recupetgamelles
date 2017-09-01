<?php

namespace AppBundle\DataFixtures\ORM;

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
class LoadRichTextData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $this->container->get('doctrine.orm.entity_manager')->getConnection()->exec('DELETE FROM rg_rich_text');

        $richTextHome = (new RichText())
            ->setIdentifier('home')
            ->setText("<p>
                RECUP & GAMELLES est une association engagée et active dans la lutte contre le gaspillage alimentaire.
                Elle met en place des actions concrètes et participatives afin d'agir et de permettre aux citoyens et aux
                acteurs de la chaîne alimentaire de s'engager et d'échanger des pratiques autour de la thématique AntiGaspi.
            </p>
            <p>
                RECUP & GAMELLES s'engage sur le territoire en faveur d'une économie sociale, écologique et solidaire et
                s'associe à tous les partenaires associatifs, entrepreunariaux et institutionnels, acteurs de cette
                dynamique.
            </p>")
        ;
        $manager->persist($richTextHome);

        $richTextAssociation = (new RichText())
            ->setIdentifier('association')
            ->setText("<p>
                    RECUP & GAMELLES est une association lyonnaise, de loi 1901, créée officiellement en décembre 2014
                    par trois fondatrices engagées depuis de nombreuses années pour les enjeux environnementaux et
                    socio-éducatifs, en vue de les partager et de les diffuser au grand public, dans un but de progression
                    sociétale (sociale, économique et environnementale). Elle s'inscrit dans une démarche d'économie sociale
                    et solidaire et a pour vocation la sensibilisation du grand public et des acteurs de l'alimentation à
                    la lutte contre le gaspillage alimentaire en développant des moyens d'actions collectives et des
                    pratiques du quotidien :
                </p>
                <p>
                    - Accompagnement des restaurateurs à la mise en place du Gourmet bag, &laquo; le doggy bag à la française &raquo;<br>
                    - Collecte d'invendus et interventions auprès des distributeurs et des producteurs.<br>
                    - Valorisation : ateliers de sensibilisation et de pratiques et bocalerie solidaire.
                </p>
                <p>
                    RECUP & GAMELLES s'intéresse à la démarche d'up-cycling et non plus seulement de recyclage, en la
                    dépassant via l'apport de valeurs ajoutées sociales et économiques sur des produits destinés à devenir
                    des déchets (exemples : fabrication de gaufres maison à partir de pain sec ou bien réalisation de pâte
                    à tartiner maison réalisée avec du chocolat issu d'invendus).
                </p>
                <p>
                    L'association prend en compte les démarches durables liées à l'alimentation dans ses pratiques : achats
                    de matières premières locales, en circuit court, en production raisonnée et/ou de l'agriculture bio,
                    réutilisation des restes, compostage des déchets fermentiscibles.
                </p>")
        ;
        $manager->persist($richTextAssociation);

        $imageGourmetBagTop = (new RichText())
            ->setIdentifier('gourmet-bag-image-top')
            ->setText('top.jpg');
        $manager->persist($imageGourmetBagTop);

        $imageGourmetBagBottom = (new RichText())
            ->setIdentifier('gourmet-bag-image-bottom')
            ->setText('bottom.png');
        $manager->persist($imageGourmetBagBottom);

        $imageAteliers = (new RichText())
            ->setIdentifier('image-atelier')
            ->setText('ateliers-boco.png');
        $manager->persist($imageAteliers);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}