# Robo String

[![CircleCI](https://circleci.com/gh/Sweetchuck/robo-string/tree/2.x.svg?style=svg)](https://circleci.com/gh/Sweetchuck/robo-string/?branch=2.x)
[![codecov](https://codecov.io/gh/Sweetchuck/robo-string/branch/2.x/graph/badge.svg?token=HSF16OGPyr)](https://app.codecov.io/gh/Sweetchuck/robo-string/branch/2.x)

This Robo task is useful when you need to do string manipulation in a
`\Robo\State\Data`.


## Install

`composer require sweetchuck/robo-string`


## Task - taskStringUnicode()

```php
<?php

declare(strict_types = 1);

use Robo\Tasks;
use Robo\State\Data as StateData;
use Sweetchuck\Robo\String\StringTaskLoader;

class RoboFileExample extends Tasks
{
    use StringTaskLoader;

    /**
     * @command string:simple
     */
    public function cmdStringSimpleExecute(string $string = 'Hello', string $suffix = 'World')
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskStringUnicode()
                    ->setString($string)
                    ->callIsEmpty()
                    ->callAppend(" $suffix")
                    ->callSnake()
            )
            ->addCode(function (StateData $data): int {
                $output = $this->output();
                $output->writeln('Is empty: ' . var_export($data['string.isEmpty'], true));
                $output->writeln("Snake: {$data['string.snake']}");
                $output->writeln("Result: {$data['string']}");

                return 0;
            });
    }
}
```

Run `vendor/bin/robo string:simple` \
Output:
<pre>Is empty: false
Snake: hello_world
Result: hello_world</pre>
