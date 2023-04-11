<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Tests\Helper\RoboFiles;

use Robo\Collection\CollectionBuilder;
use Robo\State\Data as RoboStateData;
use Robo\Tasks;
use Sweetchuck\Robo\String\StringTaskLoader;
use Symfony\Component\Yaml\Yaml;

class StringRoboFile extends Tasks
{
    use StringTaskLoader;

    /**
     * {@inheritdoc}
     */
    protected function output()
    {
        return $this->getContainer()->get('output');
    }

    public function string(string $string, string $method): CollectionBuilder
    {
        return $this
            ->collectionBuilder()
            ->addTask(
                $this
                    ->taskStringUnicode()
                    ->setString($string)
                    ->call($method)
            )
            ->addCode(function (RoboStateData $data): int {
                $assets = $data->getArrayCopy();
                unset($assets['time']);

                $this
                    ->output()
                    ->write(Yaml::dump($assets, 42, 4));

                return 0;
            });
    }
}
