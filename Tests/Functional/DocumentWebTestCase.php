<?php

namespace IDCI\Bundle\DocumentManagementBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

abstract class DocumentManagementWebTestCase extends WebTestCase
{
    /** @var Application $application */
    protected static $application;

    /** @var Client $client */
    protected $client;

    /** @var ContainerInterface $container */
    protected $container;

    public function setUp()
    {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();

        $this->container->get('twig.loader')->addPath(__DIR__.'/app/templates', '__main__');

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        self::runCommand('doctrine:database:drop --force');
    }

    /**
     * Get the application.
     *
     * @return Application
     */
    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * Run the given command.
     *
     * @param string $command
     */
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }
}
