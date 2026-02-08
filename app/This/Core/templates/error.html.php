<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 *
 * @var RequestContextInterface $context
 * @var Throwable $exception
 * @var array $exceptionFrame
 * @var array $traceFrames
 */

declare(strict_types=1);

use This\Contracts\RequestContextInterface;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>THIS – Exception</title>
    <style>
        :root {
            /* Light theme */
            --bg: #f4f6fb;
            --panel: #ffffff;
            --text: #1f2430;
            --muted: #5f6b85;
            --accent: #3b6cff;
            --error: #d64545;
            --code-bg: #f0f2f7;
            --border: #d9deea;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--text);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Inter, Helvetica, Arial, sans-serif;
            line-height: 1.5;
        }

        header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border);
            background: #cfd0d5;
        }

        .logo {
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 18px;
        }

        .logo span {
            color: var(--accent);
        }

        .tagline {
            margin-top: 6px;
            font-size: 13px;
            color: var(--muted);
        }

        main {
            padding: 32px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .exception {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .exception h1 {
            margin: 0 0 8px 0;
            font-size: 22px;
            color: var(--error);
        }

        .exception .message {
            font-size: 15px;
            color: var(--text);
        }

        .meta {
            margin-top: 12px;
            font-size: 13px;
            color: var(--muted);
        }

        .stack-frame {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: none;
            transition: box-shadow 0.3s;
        }

        .stack-header {
            padding: 12px 16px;
            background: #cfd0d5;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
        }

        .stack-header strong {
            color: var(--text);
            font-weight: 600;
        }

        .code {
            background: var(--code-bg);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            padding: 16px;
            overflow-x: auto;
            display: none;
        }

        .stack-frame.show .code {
            display: block;
        }

        .code-line {
            display: flex;
        }

        .line-number {
            width: 48px;
            text-align: right;
            padding-right: 12px;
            color: #6b708a;
            user-select: none;
        }

        .line-code {
            white-space: pre;
            flex: 1;
        }

        .highlight {
            background: rgba(255, 107, 107, 0.12);
            font-weight: 700;
        }

        footer {
            padding: 24px 32px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--muted);
            text-align: center;
        }

        .stack-frame:hover {
            box-shadow: #ddd 0 0 5px 2px;
            transition: box-shadow 0.3s;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">THIS</div>
    <div class="tagline">That Handles It Somehow</div>
</header>

<main>

    <section class="exception">
        <h1>Unhandled Exception</h1>

        <div class="message">
            <?= htmlspecialchars($exception->getMessage()) ?>
        </div>

        <div class="meta">
            <?= $exception::class ?>
            · <?= $exceptionFrame['file'] ?>:<?= $exceptionFrame['line'] ?>
        </div>
    </section>

    <section class="stack-frame show">
        <div class="stack-header">
            <strong>
                <?= $exceptionFrame['file'] ?>:<?= $exceptionFrame['line'] ?>
            </strong>
        </div>

        <div class="code">
            <?php foreach ($exceptionFrame['code'] as $line): ?>
                <div class="code-line <?= $line['highlight'] ? 'highlight' : '' ?>">
                    <div class="line-number"><?= $line['number'] ?></div>
                    <div class="line-code"><?= htmlspecialchars($line['content']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if (!empty($traceFrames)): ?>
        <h3>Exception trace:</h3>
    <?php endif; ?>

    <?php foreach ($traceFrames as $frame): ?>
        <section class="stack-frame">

            <div class="stack-header">
                <?php if ($frame['class']): ?>
                    <strong><?= $frame['class'] ?>::<?= $frame['function'] ?>()</strong><br />
                <?php endif; ?>
                <?= $frame['file'] ?>:<?= $frame['line'] ?>
            </div>

            <div class="code">
                <?php foreach ($frame['code'] as $line): ?>
                    <div class="code-line <?= $line['highlight'] ? 'highlight' : '' ?>">
                        <div class="line-number"><?= $line['number'] ?></div>
                        <div class="line-code"><?= htmlspecialchars($line['content']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

        </section>
    <?php endforeach; ?>

</main>

<footer>
    THIS Framework · Debug mode · Execution stopped
</footer>

<script src="https://code.jquery.com/jquery-4.0.0.min.js"></script>
<script>
$(function () {
    $(document).on('click', '.stack-header', function () {
        let $frame = $(this).closest('.stack-frame').toggleClass('show');
    });
});
</script>

</body>
</html>

