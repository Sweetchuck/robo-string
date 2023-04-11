<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Tests\Unit\Task;

use Codeception\Test\Unit;
use League\Container\Container as LeagueContainer;
use Psr\Container\ContainerInterface;
use Robo\Application as RoboApplication;
use Robo\Collection\CollectionBuilder;
use Robo\Config\Config;
use Robo\Robo;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyOutput;
use Sweetchuck\Codeception\Module\RoboTaskRunner\DummyProcess;
use Sweetchuck\Robo\String\Tests\Helper\Dummy\DummyTaskBuilder;
use Sweetchuck\Robo\String\Tests\UnitTester;
use Symfony\Component\ErrorHandler\BufferingLogger;

abstract class TaskTestBase extends Unit
{

    protected ContainerInterface $container;

    protected Config $config;

    protected CollectionBuilder $builder;

    protected UnitTester $tester;

    /**
     * @var \Sweetchuck\Robo\String\Task\StringUnicodeTask|\Robo\Collection\CollectionBuilder
     */
    protected $task;

    protected DummyTaskBuilder $taskBuilder;

    /**
     * @inheritdoc
     */
    public function _before()
    {
        parent::_before();

        Robo::unsetContainer();
        DummyProcess::reset();

        $this->container = new LeagueContainer();
        $application = new RoboApplication('Sweetchuck - Robo String', '2.0.0');
        $this->config = new Config();
        $input = null;
        $output = new DummyOutput([
            'verbosity' => DummyOutput::VERBOSITY_DEBUG,
        ]);

        $this->container->add('container', $this->container);

        Robo::configureContainer($this->container, $application, $this->config, $input, $output);
        $this->container->addShared('logger', BufferingLogger::class);

        $this->builder = CollectionBuilder::create($this->container, null);
        $this->taskBuilder = new DummyTaskBuilder();
        $this->taskBuilder->setContainer($this->container);
        $this->taskBuilder->setBuilder($this->builder);

        $this->initTask();
    }

    abstract protected function initTask(): static;
}
