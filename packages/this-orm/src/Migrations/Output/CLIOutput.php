<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Output;

final readonly class CLIOutput
{


    private bool $useColors;

    private bool $interactive;

    public function __construct(
        private CLIMarkupRenderer $renderer,
        ?bool $useColors = null,
    ) {
        $this->interactive = $this->detectInteractive();
        $this->useColors   = $useColors ?? $this->detectColors();
    }

    public function line(string $message = ''): void
    {
        $this->write($message);
    }

    public function info(string $message): void
    {
        $this->write($this->label('INFO', '30', '44') . " <t:blue>$message</t:blue>");
    }

    public function success(string $message): void
    {
        $this->write($this->label('OK', '30', '42') . " <t:green>$message</t:green>");
    }

    public function warning(string $message): void
    {
        $this->write($this->label('WARNING', '30', '43') . " <t:yellow>$message</t:yellow>");
    }

    public function error(string $message): void
    {
        $this->write(
            $this->label('ERROR', '30', '41') . " <t:red>$message</t:red>",
            true
        );
    }

    /**
     * Ask user for arbitrary input.
     *
     * - returns user input as-is
     * - Enter returns default value
     * - in non-interactive mode returns default or empty string
     */
    public function ask(string $question, ?string $default = null): string
    {
        if (!$this->interactive) {
            return $default ?? '';
        }

        $suffix = $default !== null
            ? " [$default] "
            : ' ';

        $this->writeInline(
            $this->label('?', '30', '46') . ' ' . $question . $suffix
        );

        $input = trim((string) fgets(STDIN));

        if ($input === '') {
            return $default ?? '';
        }

        return $input;
    }

    /**
     * Yes / No confirmation sugar.
     */
    public function confirm(string $question, bool $default = false): bool
    {
        $defaultText = $default ? 'yes' : 'no';

        $answer = strtolower(
            $this->ask($question . ' (yes/no)', $defaultText)
        );

        return in_array($answer, ['y', 'yes'], true);
    }

    /**
     * Warn and abort execution unless user confirms.
     */
    public function confirmOrAbort(string $message): void
    {
        $this->warning($message);

        if (!$this->confirm('Do you want to continue?')) {
            $this->line();
            $this->error('Operation aborted by user.');

            exit(1);
        }
    }

    private function write(string $message, bool $stderr = false): void
    {
        $message = $this->renderer->render($message, $this->useColors);

        fwrite(
            $stderr ? STDERR : STDOUT,
            $message . PHP_EOL
        );
    }

    private function writeInline(string $message): void
    {
        fwrite(STDOUT, $message);
    }

    private function label(string $text, string $fg, string $bg): string
    {
        if (!$this->useColors) {
            return trim($text);
        }

        $text = str_pad("$text ", 10, ' ', STR_PAD_LEFT);

        return "\033[1;{$fg};{$bg}m{$text}\033[0m";
    }

    private function detectColors(): bool
    {
        if (PHP_SAPI !== 'cli') {
            return false;
        }

        if (!function_exists('posix_isatty')) {
            return false;
        }

        return posix_isatty(STDOUT);
    }

    private function detectInteractive(): bool
    {
        if (PHP_SAPI !== 'cli') {
            return false;
        }

        if (!function_exists('posix_isatty')) {
            return false;
        }

        return posix_isatty(STDIN);
    }
}
