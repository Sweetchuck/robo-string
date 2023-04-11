<?php

declare(strict_types = 1);

namespace Sweetchuck\Robo\String\Task;

use Symfony\Component\String\UnicodeString;

/**
 * @method $this callAfterLast(string|iterable $needle, bool $includeNeedle = false, int $offset = 0)
 * @method $this callAfter(string|iterable $needle, bool $includeNeedle = false, int $offset = 0)
 * @method $this callAppend(string ...$suffix)
 * @method $this callAscii(array $rules = [])
 * @method $this callBeforeLast(string|iterable $needle, bool $includeNeedle = false, int $offset = 0)
 * @method $this callBefore(string|iterable $needle, bool $includeNeedle = false, int $offset = 0)
 * @method $this callBytesAt(int $offset)
 * @method $this callCamel()
 * @method $this callChunk(int $length = 1)
 * @method $this callCodePointsAt(int $offset)
 * @method $this callCollapseWhitespace()
 * @method $this callContainsAny(string|iterable $needle)
 * @method $this callEndsWith(string|iterable|\Symfony\Component\String\AbstractString $suffix)
 * @method $this callEnsureEnd(string $suffix)
 * @method $this callEnsureStart(string $prefix)
 * @method $this callEqualsTo(string|iterable|\Symfony\Component\String\AbstractString $string)
 * @method $this callFolded(bool $compat = true)
 * @method $this callIgnoreCase()
 * @method $this callIndexOfLast(string|iterable $needle, int $offset = 0)
 * @method $this callIndexOf(string|iterable|\Symfony\Component\String\AbstractString $needle, int $offset = 0)
 * @method $this callIsEmpty()
 * @method $this callJoin(array $strings, string $lastGlue = null)
 * @method $this callLength()
 * @method $this callLower()
 * @method $this callMatch(string $regexp, int $flags = 0, int $offset = 0)
 * @method $this callNormalize(int $form = \Symfony\Component\String\UnicodeString::NFC)
 * @method $this callPadBoth(int $length, string $padStr = ' ')
 * @method $this callPadEnd(int $length, string $padStr = ' ')
 * @method $this callPadStart(int $length, string $padStr = ' ')
 * @method $this callPrepend(string ...$prefix)
 * @method $this callRepeat(int $multiplier)
 * @method $this callReplaceMatches(string $fromRegexp, string|callable $to)
 * @method $this callReplace(string $from, string $to)
 * @method $this callReverse()
 * @method $this callSlice(int $start = 0, int $length = null)
 * @method $this callSnake()
 * @method $this callSplice(string $replacement, int $start = 0, int $length = null)
 * @method $this callSplit(string $delimiter, int $limit = null, int $flags = null)
 * @method $this callStartsWith(string|iterable|\Symfony\Component\String\AbstractString $prefix)
 * @method $this callTitle(bool $allWords = false)
 * @method $this callToByteString(string $toEncoding = null)
 * @method $this callToCodePointString()
 * @method $this callToString()
 * @method $this callToUnicodeString()
 * @method $this callTrimEnd(string $chars = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}")
 * @method $this callTrimPrefix($prefix)
 * @method $this callTrimStart(string $chars = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}")
 * @method $this callTrim(string $chars = " \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}")
 * @method $this callTrimSuffix($suffix)
 * @method $this callTruncate(int $length, string $ellipsis = '', bool $cut = true)
 * @method $this callUpper()
 * @method $this callWidth(bool $ignoreAnsiDecoration = true)
 * @method $this callWordwrap(int $width = 75, string $break = "\n", bool $cut = false)
 */
class StringUnicodeTask extends StringBaseTask
{

    public function runInit(): static
    {
        $this->text = new UnicodeString($this->getString());

        return $this;
    }
}
