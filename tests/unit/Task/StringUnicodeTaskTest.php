<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Tests\Unit\Task;

/**
 * @covers \Sweetchuck\Robo\String\Task\StringUnicodeTask
 * @covers \Sweetchuck\Robo\String\Task\StringBaseTask
 * @covers \Sweetchuck\Robo\String\StringTaskLoader
 */
class StringUnicodeTaskTest extends TaskTestBase
{

    protected function initTask(): static
    {
        $this->task = $this->taskBuilder->taskStringUnicode();

        return $this;
    }

    public function casesRun(): array
    {
        return [
            'basic' => [
                [
                    'assets' => [
                        'my.camelCase' => 'fooBarBaz',
                        'my.string.length' => 9,
                        'my.string.isEmpty' => false,
                        'my.string' => 'prefix.fooBarBaz',
                    ],
                ],
                [
                    'assetNamePrefix' => 'my.',
                    'string' => 'foo-bar-baz',
                    'queue' => [
                        'snake',
                        [
                            'method' => 'camel',
                            'assetName' => 'camelCase',
                        ],
                        'length',
                        'isEmpty',
                        [
                            'method' => 'prepend',
                            'args' => ['prefix.'],
                        ],
                    ],
                ],
            ],
            'git commit-msg filter' => [
                [
                    'assets' => [
                        'string' => implode(PHP_EOL, [
                            'Subject',
                            '',
                            'Long',
                            'body.',
                        ]),
                    ],
                ],
                [
                    'string' => implode(PHP_EOL, [
                        'Subject',
                        '',
                        'Long',
                        'body.',
                        '# a',
                        '# b',
                        '# c',
                        ''
                    ]),
                    'queue' => [
                        [
                            'method' => 'replaceMatches',
                            'args' => [
                                '/(^|(\r\n)|(\n\r)|\r|\n)#([^\r\n]*)|$/',
                                '',
                            ],
                        ],
                        'trim',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider casesRun
     */
    public function testRun(array $expected, array $options): void
    {
        $result = $this
            ->task
            ->setOptions($options)
            ->run();

        if (array_key_exists('assets', $expected)) {
            foreach ($expected['assets'] as $assetName => $assetValue) {
                $this->tester->assertSame(
                    $assetValue,
                    $result[$assetName],
                    "Asset '$assetName'"
                );
            }
        }
    }

    public function testMagickMethodCallStartsWithCallSuccess(): void
    {
        $this->tester->assertFalse(method_exists($this->task, 'callIsEmpty'));

        $result = $this
            ->task
            ->setString('0')
            ->callIsEmpty()
            ->run();

        $this->tester->assertFalse($result['string.isEmpty']);
        $this->tester->assertSame('0', $result['string']);
    }

    public function testMagicMethodCallStartsWithCallFail(): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("has no callable method: Symfony\Component\String\UnicodeString::notExists");
        $this
            ->task
            ->callNotExists()
            ->run();
    }

    public function testMagicMethodCallTotallyWrong(): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("Method 'Sweetchuck\Robo\String\Task\StringUnicodeTask::fooBar' does not exists");
        $this->task->fooBar();
    }

    /**
     * Non existing String method added as queue item.
     */
    public function testRunMethodNotExists(): void
    {
        $this->expectExceptionCode(1);
        $this->expectExceptionMessage("has no callable method: Symfony\Component\String\UnicodeString::notExists");
        $this
            ->task
            ->addToQueue(['method' => 'notExists'])
            ->run();
    }
}
