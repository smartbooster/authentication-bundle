<?php

namespace Smart\AuthenticationBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Fidry\AliceDataFixtures\Loader\PurgerLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Nicolas Bastien <nicolas.bastien@smartbooster.io>.
 */
abstract class AbstractFixtures extends Fixture
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $fixturesDir;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    protected function getFixturesDir()
    {
        return $this->container->getParameter('kernel.project_dir').'/fixtures/';
    }

    /**
     * @return PurgerLoader
     */
    protected function getLoader()
    {
        return $this->container->get('fidry_alice_data_fixtures.loader.doctrine');
    }
}
