<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String;

use Robo\Collection\CollectionBuilder;

trait StringTaskLoader
{
    /**
     * @phpstan-param robo-string-task-options $options
     *
     * @return \Sweetchuck\Robo\String\Task\StringUnicodeTask&\Robo\Collection\CollectionBuilder
     */
    protected function taskStringUnicode(array $options = []): CollectionBuilder
    {
        /** @var \Sweetchuck\Robo\String\Task\StringUnicodeTask&\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\StringUnicodeTask::class);
        $task->setOptions($options);

        return $task;
    }
}
