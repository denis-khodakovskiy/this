<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Output\CLI;

final class CLIMarkupRenderer
{
    private const FG = [
        'black' => '30', 'red' => '31', 'green' => '32',
        'yellow' => '33', 'blue' => '34', 'magenta' => '35',
        'cyan' => '36', 'white' => '37',
    ];

    private const BG = [
        'black' => '40', 'red' => '41', 'green' => '42',
        'yellow' => '43', 'blue' => '44', 'magenta' => '45',
        'cyan' => '46', 'white' => '47',
    ];

    private const STYLE = [
        'b' => '1',
        'u' => '4',
    ];

    public function render(string $text, bool $useColors = true): string
    {
        if (!$useColors) {
            return preg_replace('/<\/?[^>]+>/', '', $text);
        }

        $stack = [];
        $out = '';

        $tokens = preg_split(
            '/(<\/?[^>]+>)/',
            $text,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        foreach ($tokens as $token) {
            if ($this->isTag($token)) {
                if ($this->isClosingTag($token)) {
                    array_pop($stack);
                    $out .= "\033[0m" . $this->applyStack($stack);
                } else {
                    $style = $this->parseTag($token);
                    if ($style !== null) {
                        $stack[] = $style;
                        $out .= $this->applyStyle($style);
                    }
                }
            } else {
                $out .= $token;
            }
        }

        return $out . "\033[0m";
    }

    /* ---------------- internals ---------------- */

    private function isTag(string $token): bool
    {
        return $token !== ''
            && $token[0] === '<'
            && $token[strlen($token) - 1] === '>';
    }

    private function isClosingTag(string $token): bool
    {
        return str_starts_with($token, '</');
    }

    private function parseTag(string $tag): ?array
    {
        $tag = trim($tag, '<>');

        if (isset(self::STYLE[$tag])) {
            return ['type' => 'style', 'code' => self::STYLE[$tag]];
        }

        if (str_contains($tag, ':')) {
            [$kind, $value] = explode(':', $tag, 2);

            if ($kind === 't' && isset(self::FG[$value])) {
                return ['type' => 'fg', 'code' => self::FG[$value]];
            }

            if ($kind === 'b' && isset(self::BG[$value])) {
                return ['type' => 'bg', 'code' => self::BG[$value]];
            }
        }

        return null;
    }

    private function applyStyle(array $style): string
    {
        return "\033[{$style['code']}m";
    }

    private function applyStack(array $stack): string
    {
        $codes = array_column($stack, 'code');

        if ($codes === []) {
            return '';
        }

        return "\033[" . implode(';', $codes) . "m";
    }
}
