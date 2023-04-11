<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Tests\Acceptance\Task;

use Sweetchuck\Robo\String\Tests\AcceptanceTester;
use Sweetchuck\Robo\String\Tests\Helper\RoboFiles\StringRoboFile;

/**
 * @covers \Sweetchuck\Robo\String\Task\StringUnicodeTask
 * @covers \Sweetchuck\Robo\String\Task\StringBaseTask
 * @covers \Sweetchuck\Robo\String\StringTaskLoader
 */
class StringUnicodeTaskCest
{
    public function runStringLower(AcceptanceTester $tester)
    {
        $id = 'stringLower';
        $tester->runRoboTask($id, StringRoboFile::class, 'string', 'fooBAR', 'lower');
        $exitCode = $tester->getRoboTaskExitCode($id);
        $stdOutput = $tester->getRoboTaskStdOutput($id);
        $stdError = $tester->getRoboTaskStdError($id);

        $tester->assertSame(0, $exitCode);
        $tester->assertStringContainsString('string.lower: foobar' . PHP_EOL, $stdOutput);
        $tester->assertStringContainsString('string: foobar' . PHP_EOL, $stdOutput);
        $tester->assertSame(' [String] lower' . PHP_EOL, $stdError);
    }

    public function runStringUpper(AcceptanceTester $tester)
    {
        $id = 'stringUpper';
        $tester->runRoboTask($id, StringRoboFile::class, 'string', 'fooBAR', 'upper');
        $exitCode = $tester->getRoboTaskExitCode($id);
        $stdOutput = $tester->getRoboTaskStdOutput($id);
        $stdError = $tester->getRoboTaskStdError($id);

        $tester->assertSame(0, $exitCode);
        $tester->assertStringContainsString('string.upper: FOOBAR' . PHP_EOL, $stdOutput);
        $tester->assertStringContainsString('string: FOOBAR' . PHP_EOL, $stdOutput);
        $tester->assertSame(' [String] upper' . PHP_EOL, $stdError);
    }
}
