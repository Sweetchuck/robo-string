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

class RoboFile extends \Robo\Tasks
{
    use \Sweetchuck\Robo\String\StringTaskLoader;
    
    /**
     * @command string:simple
     */
    public function cmdStringSimple(string $text = 'Hello', string $suffix = 'World')
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskStringUnicode()
                    ->setString($text)
                    ->callIsUpperCase()
                    ->callAppend(" $suffix")
                    ->callUnderscored()
            )
            ->addCode(function (\Robo\State\Data $data): int {
                $output = $this->output();
                $output->writeln('Is upper case: ' . var_export($data['string.isUpperCase'], true));
                $output->writeln("Result: {$data['string']}");

                return 0;
            });
    }
}
```

Run `vendor/bin/robo string:simple` \
Output:
> <pre>Is upper case: false
> Result: hello_world</pre>

Run `vendor/bin/robo string:simple FOO` \
Output:
> <pre>Is upper case: true
> Result: f_o_o_world</pre>
