<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Task;

use BadMethodCallException;
use Robo\Result;
use Robo\TaskInfo;
use Symfony\Component\String\AbstractString;
use Symfony\Component\String\UnicodeString;

abstract class StringBaseTask extends \Robo\Task\BaseTask implements \Stringable
{
    protected string $taskName = 'String';

    /**
     * @phpstan-var  array<robo-string-queue-item-full>
     */
    protected array $queue = [];

    /**
     * @phpstan-var  array<string, mixed>
     */
    protected array $assets = [];

    // region Options

    // region assetNamePrefix.
    protected string $assetNamePrefix = '';

    public function getAssetNamePrefix(): string
    {
        return $this->assetNamePrefix;
    }

    /**
     * @return $this
     */
    public function setAssetNamePrefix(string $value)
    {
        $this->assetNamePrefix = $value;

        return $this;
    }
    // endregion

    // region string
    protected string $string = '';

    public function getString(): string
    {
        return $this->string;
    }

    public function setString(string $value): static
    {
        $this->string = $value;

        return $this;
    }
    // endregion

    protected AbstractString $text;

    // endregion

    public function __toString(): string
    {
        return $this->getTaskName();
    }

    /**
     * @param string $name
     * @param array<mixed> $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^call[A-Z]/', $name)) {
            $first = mb_strtolower(mb_substr($name, 4, 1));
            $method = $first . mb_substr($name, 5);

            return $this->call($method, $arguments);
        }

        throw new BadMethodCallException(
            sprintf(
                "Method '%s::%s' does not exists",
                get_called_class(),
                $name,
            ),
            1,
        );
    }

    /**
     * @phpstan-param robo-string-task-options $options
     */
    public function setOptions(array $options): static
    {
        if (array_key_exists('string', $options)) {
            $this->setString($options['string']);
        }

        if (array_key_exists('queue', $options)) {
            foreach ($options['queue'] as $item) {
                $this->addToQueue($item);
            }
        }

        if (array_key_exists('assetNamePrefix', $options)) {
            $this->setAssetNamePrefix($options['assetNamePrefix']);
        }

        return $this;
    }

    /**
     * @phpstan-param string|robo-string-queue-item $item
     */
    public function addToQueue(string|array $item): static
    {
        if (is_string($item)) {
            $item = ['method' => $item];
        }

        $item['index'] = count($this->queue);
        $this->queue[] = $item + [
                'args' => [],
                'weight' => 0,
                'assetName' => null,
            ];

        return $this;
    }

    /**
     * @param string $method
     * @param array<mixed> $args
     * @param null|string $assetName
     */
    public function call(string $method, array $args = [], ?string $assetName = null): static
    {
        $item = [
            'method' => $method,
            'args' => $args,
            'assetName' => $assetName,
        ];

        return $this->addToQueue($item);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this
            ->runInit()
            ->runHeader()
            ->runDoIt()
            ->runReturn();
    }

    abstract protected function runInit(): static;

    protected function runHeader(): static
    {
        $this->printTaskDebug('{stringMethodsComma}');

        return $this;
    }

    protected function runDoIt(): static
    {
        $assetNameBase = $this->getAssetNameBase();
        foreach ($this->queue as $item) {
            $this->assertStringMethod($item['method']);

            $result = $this->text->{$item['method']}(...$item['args']);
            if (!isset($item['assetName'])) {
                // @todo Same method can be in the queue multiple times.
                $item['assetName'] = "{$assetNameBase}.{$item['method']}";
            }

            if ($item['assetName'] !== false) {
                $this->addToAssets($item['assetName'], $result);
            }

            if ($result instanceof AbstractString) {
                $this->text = $result;
            }
        }

        $this->addToAssets($assetNameBase, $this->text);

        return $this;
    }

    protected function addToAssets(string $name, mixed $result): static
    {
        $this->assets[$name] = $result instanceof AbstractString ?
            (string) $result
            : $result;

        return $this;
    }

    protected function runReturn(): Result
    {
        return new Result(
            $this,
            0,
            '',
            $this->getAssetsWithPrefixedNames(),
        );
    }

    /**
     * @phpstan-return array<string, mixed>
     */
    protected function getAssetsWithPrefixedNames(): array
    {
        $prefix = $this->getAssetNamePrefix();
        if (!$prefix) {
            return $this->assets;
        }

        $assets = [];
        foreach ($this->assets as $key => $value) {
            $assets["{$prefix}{$key}"] = $value;
        }

        return $assets;
    }

    public function getTaskName(): string
    {
        return $this->taskName ?: TaskInfo::formatTaskName($this);
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-param ?array<string, mixed> $context
     *
     * @phpstan-return array<string, mixed>
     */
    protected function getTaskContext($context = null)
    {
        if (!$context) {
            $context = [];
        }

        if (empty($context['name'])) {
            $context['name'] = $this->getTaskName();
        }

        if (empty($context['stringMethodsComma'])) {
            $context['stringMethodsComma'] = implode(', ', $this->getMethods());
        }

        return parent::getTaskContext($context);
    }

    protected function getAssetNameBase(): string
    {
        return (new UnicodeString($this->getTaskName()))
            ->camel()
            ->toString();
    }

    /**
     * @return string[]
     */
    protected function getMethods(): array
    {
        $methods = [];
        foreach ($this->queue as $item) {
            $methods[] = $item['method'];
        }

        return $methods;
    }

    protected function assertStringMethod(string $method): void
    {
        if (!is_callable([$this->text, $method])) {
            throw  new BadMethodCallException(
                sprintf(
                    'has no callable method: %s::%s',
                    get_class($this->text),
                    $method,
                ),
                1,
            );
        }
    }
}
