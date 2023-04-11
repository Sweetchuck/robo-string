<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Tests\Helper\Dummy;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Collection\CollectionBuilder;
use Robo\Common\TaskIO;
use Robo\Contract\BuilderAwareInterface;
use Robo\State\StateAwareTrait;
use Robo\TaskAccessor;
use Robo\Tasks as RoboTasks;
use Sweetchuck\Robo\String\StringTaskLoader;

class DummyTaskBuilder implements BuilderAwareInterface, ContainerAwareInterface
{
    use TaskAccessor;
    use ContainerAwareTrait;
    use StateAwareTrait;
    use TaskIO;

    use StringTaskLoader {
        taskStringUnicode as public;
    }

    public function collectionBuilder(): CollectionBuilder
    {
        $commandFile = new RoboTasks();

        return CollectionBuilder::create($this->getContainer(), $commandFile);
    }
}
