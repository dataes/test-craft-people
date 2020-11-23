<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class AbstractController extends WebTestCase
{
    /** @var EntityManagerInterface $manager */
    protected $manager;
    /** @var ORMExecutor $executor */
    private $executor;
    /** @var AbstractBrowser $client */
    protected $client;
    /** @var Factory $faker */
    protected $faker;

    public function setUp()
    {
        $this->client = static::createClient();

        // Configure variables
        $this->manager = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->executor = new ORMExecutor($this->manager, new ORMPurger());
        $this->faker = Factory::create();

        // Run the schema update tool using our entity metadata
        $schemaTool = new SchemaTool($this->manager);
        $schemaTool->updateSchema($this->manager->getMetadataFactory()->getAllMetadata());
    }

    protected function loadFixture($fixture)
    {
        $loader = new Loader();
        $fixtures = is_array($fixture) ? $fixture : [$fixture];
        foreach ($fixtures as $item) {
            $loader->addFixture($item);
        }
        $this->executor->execute($loader->getFixtures());
    }

    public function tearDown()
    {
        (new SchemaTool($this->manager))->dropDatabase();
    }
}