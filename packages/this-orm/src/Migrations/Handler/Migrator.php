<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Handler;

use This\Contracts\RequestInterface;
use This\ORM\DQL\Expr;
use This\ORM\Migrations\Compiler\Compiler;
use This\ORM\Migrations\Command\CreateIndexCommand;
use This\ORM\Migrations\Command\CreateTableCommand;
use This\ORM\Migrations\Migration;
use This\ORM\Migrations\Registry\RegistrySchema;
use This\ORM\Migrations\Schema\SchemaBuilder;
use This\ORM\Migrations\Schema\SchemaCommandCollector;
use This\ORM\Migrations\Schema\TableBuilder;
use This\ORM\Migrations\Schema\TableCommandCollector;
use This\ORM\ORMInterface;
use This\ORM\Query\Delete;
use This\ORM\Query\Insert;
use This\ORM\Query\Select;
use This\ORM\Query\Update;
use This\Output\CLI\CLIMarkupRenderer;
use This\Output\CLI\CLIOutput;

final readonly class Migrator
{
    private CLIOutput $output;

    public function __construct(
        private string $migrationsPath,
        private RequestInterface $request,
        private Compiler $compiler,
        private ORMInterface $orm,
    ) {
        $this->output = new CLIOutput(new CLIMarkupRenderer());
    }

    /**
     * @throws \Exception
     */
    public function __invoke(string $action): void
    {
        if (
            !file_exists($this->migrationsPath)
            || !is_dir($this->migrationsPath)
            || !is_writable($this->migrationsPath)
        ) {
            $this->output->error("Migrations directory <b>{$this->migrationsPath}</b> does not exist or is not writable");

            exit(1);
        }

        try {
            match ($action) {
                'create' => $this->create(),
                'migrate' => $this->migrate(),
                'fix' => $this->fix(),
                'rollback' => $this->rollback(),
                default => $this->output->error("Unknown action: <b>{$action}</b>"),
            };
        } catch (\Throwable $e) {
            $this->output->error($e->getMessage());
        }

        exit(1);
    }

    private function create(): void
    {
        $this->output->confirmOrAbort('Are you sure you want to create a new migration?');
        $this->output->info('Generating migration file...');

        $name = $this->request->getAttribute('name', $this->request->getAttribute(0)) ?? 'auto_generated';
        $time = (new \DateTimeImmutable())->format('YmdHis');

        $code = file_get_contents(__DIR__ . '/../Stubs/Migration.stub');
        $code = str_replace(['{{name}}', '{{time}}'], [$name, $time], $code);

        file_put_contents("{$this->migrationsPath}/Version{$time}_{$name}.php", $code);

        $this->output->success(sprintf('New migration file <b>%s</b> has been created successfully', "{$this->migrationsPath}/Version{$time}_{$name}.php"));
    }

    /**
     * @throws \Exception
     */
    private function migrate(): void
    {
        $this->output->line();

        $dryRun = $this->request->getAttribute('dry-run', false);
        $noInteraction = $this->request->getAttribute('no-interaction', false);

        if ($dryRun) {
            $this->output->info('Dry run mode is enabled. No migrations will be applied');
        }

        if ($noInteraction) {
            $this->output->info('No interaction mode is enabled. Migrations will be applied without user confirmation');
        }

        if (!$noInteraction) {
            $this->output->confirmOrAbort('Are you sure you want to migrate the database?');
        }

        $this->checkMigrationsRegistry();

        $migrations = array_map(
            static fn (string $file) => str_replace('.php', '', $file),
            array_values(
                array_filter(
                    scandir($this->migrationsPath),
                    fn (string $file) => preg_match('/^Version\d+_.+\.php$/', $file),
                )
            )
        );

        $query = Select::from(RegistrySchema::class)->orderBy(['id' => 'DESC']);
        $applied = $this->orm->query($query)->execute();
        $appliedMap = [];

        foreach ($applied as $migration) {
            $appliedMap[$migration['version']] = $migration;
        }

        $missingMigrationFiles = array_diff(array_keys($appliedMap), $migrations);

        if ($missingMigrationFiles !== []) {
            $this->output->warning('The following migrations are missing:');

            foreach ($missingMigrationFiles as $migrationFile) {
                $this->output->warning($migrationFile);
            }

            if (!$noInteraction) {
                $this->output->confirmOrAbort('These migrations were applied earlier, but are missing in the filesystem');
            }
        }

        $totalTime = 0;

        foreach ($migrations as $migrationFile) {
            $this->output->line(str_pad('', 80, '-', STR_PAD_BOTH));

            if (isset($appliedMap[$migrationFile])) {
                $checksum = hash('sha256', file_get_contents("{$this->migrationsPath}/{$migrationFile}.php"));

                if ($checksum !== $appliedMap[$migrationFile]['checksum']) {
                    $this->output->error("Migration checksum mismatch: <b>{$migrationFile}</b>");
                    $this->output->error("<b>Applied checksum:</b> {$appliedMap[$migrationFile]['checksum']}");
                    $this->output->error("<b>Current checksum:</b> {$checksum}");
                    $this->output->error('This usually means migration was modified after execution.');
                    $this->output->info("You can fix this by running: <b>/migration/fix --version={$migrationFile}</b>");
                    $this->output->error('Aborting migration execution');
                    $this->output->line();

                    exit(1);
                }

                continue;
            }

            require_once "{$this->migrationsPath}/{$migrationFile}.php";
            $migration = new $migrationFile();
            assert($migration instanceof Migration);

            if ($migration->draft()) {
                $this->output->error("Migration <u><b>{$migrationFile}</b></u> is marked as draft and cannot be applied. <b><u>Reason:</u></b> {$migration->draftReason()}");
                $this->output->error('Aborting migration execution');
                $this->output->line();

                exit(1);
            }

            $this->output->info("Applying migration: <b>{$migrationFile}</b>...");

            $commandCollector = new SchemaCommandCollector();
            $schemaBuilder = new SchemaBuilder($commandCollector);
            $start = microtime(true);
            $migration->up($schemaBuilder);

            foreach ($commandCollector->getCommands() as $command) {
                if ($command->getDescription()) {
                    $this->output->info($command->getDescription());
                }

                $sql = $this->compiler->compile($command);

                if (!$dryRun) {
                    $this->orm->rawSql($this->compiler->compile($command));
                } else {
                    $this->output->line($sql);
                }
            }

            $end = microtime(true);
            $executionTime = round(($end - $start) * 1000);
            $this->output->success("Migration <u>{$migrationFile}</u> has been applied successfully in {$executionTime}ms");

            if (!$dryRun) {
                $query = Insert::into(RegistrySchema::class)->values([
                    'version' => $migrationFile,
                    'checksum' => hash('sha256', file_get_contents("{$this->migrationsPath}/{$migrationFile}.php")),
                    'execution_time' => $executionTime,
                ]);
                $this->orm->query($query)->execute();
            }

            $totalTime += $executionTime;
        }

        $this->output->line(str_pad('', 80, '-', STR_PAD_BOTH));
        $this->output->success("<b>All migrations have been applied successfully in {$totalTime} ms</b>");
        $this->output->line();
    }

    private function fix(): void
    {
        $version = $this->request->getAttribute('version');
        $this->output->confirmOrAbort("Are you sure you want to fix migration <b>{$version}</b> checksum?");

        if (!file_exists("{$this->migrationsPath}/{$version}.php")) {
            $this->output->error("Migration file <b>{$version}.php</b> does not exist");
            $this->output->line();

            exit(1);
        }

        $query = Select::from(RegistrySchema::class)->where(Expr::equal('version', $version));
        $migration = $this->orm->query($query)->first();

        if (!$migration) {
            $this->output->error("Migration <b>{$version}</b> is not applied yet");
            $this->output->line();

            exit(1);
        }

        $checksum = hash('sha256', file_get_contents("{$this->migrationsPath}/{$version}.php"));

        if ($checksum === $migration['checksum']) {
            $this->output->success("Migration <b>{$version}</b> checksum is up to date");
            $this->output->line();

            exit(1);
        }

        $this->output->info("Current checksum: <b>{$migration['checksum']}</b>");
        $this->output->info("New checksum: <b>{$checksum}</b>");

        $query = Update::table(RegistrySchema::class)
            ->set(['checksum' => $checksum])
            ->where(Expr::equal('version', $version))
        ;

        if (!$this->orm->query($query)->execute()) {
            $this->output->error("Failed to update checksum for migration <b>{$version}</b>");
            $this->output->line();

            exit(1);
        }

        $this->output->success("Checksum for migration <b>{$version}</b> has been updated successfully");
        $this->output->line();
    }

    public function rollback(): void
    {
        $this->output->line();

        $dryRun = $this->request->getAttribute('dry-run', false);

        if ($dryRun) {
            $this->output->info('Dry run mode is enabled. No migrations will be rolled back');
        }

        $this->output->confirmOrAbort('Are you sure you want to rollback the database?');
        $amount = $this->request->getAttribute('n', $this->request->getAttribute(0));

        if ($amount === null) {
            $this->output->error('Please specify the number of migrations to rollback using --n=N flag');
            $this->output->line();

            exit(1);
        }

        $migrationsToRollback = $this->orm->query(
            Select::from(RegistrySchema::class)
                ->orderBy(['id' => 'DESC'])
                ->limit((int) $amount)
        )->execute();

        $this->output->line();
        $this->output->warning('You are about to rollback the following migrations:');

        foreach ($migrationsToRollback as $migration) {
            $this->output->warning($migration['version']);
        }

        if (!$this->output->confirm('Continue? [y/n]')) {
            $this->output->success('Rollback aborted by user');
            $this->output->line();

            exit(1);
        }

        $totalTime = 0;

        foreach ($migrationsToRollback as $migrationData) {
            $this->output->line(str_pad('', 80, '-', STR_PAD_BOTH));
            $this->output->info("Rolling back migration: <b>{$migrationData['version']}</b>...");

            require_once "{$this->migrationsPath}/{$migrationData['version']}.php";
            $migration = new $migrationData['version']();

            assert($migration instanceof Migration);

            $schemaBuilder = new SchemaBuilder(new SchemaCommandCollector());

            $commandCollector = new SchemaCommandCollector();
            $schemaBuilder = new SchemaBuilder($commandCollector);
            $start = microtime(true);
            $migration->down($schemaBuilder);

            foreach ($commandCollector->getCommands() as $command) {
                if ($command->getDescription()) {
                    $this->output->info($command->getDescription());
                }

                $sql = $this->compiler->compile($command);

                if (!$dryRun) {
                    $this->orm->rawSql($sql);
                } else {
                    $this->output->line($sql);
                }
            }

            $end = microtime(true);
            $executionTime = round(($end - $start) * 1000);

            if (!$dryRun) {
                $this->orm
                    ->query(Delete::from(RegistrySchema::class)->where(Expr::equal('id', $migrationData['id'])))
                    ->execute()
                ;
            }

            $this->output->success("Migration <u>{$migrationData['version']}</u> has been rolled back successfully in {$executionTime}ms");

            $totalTime += $executionTime;
        }

        $this->output->line(str_pad('', 80, '-', STR_PAD_BOTH));
        $this->output->success("<b>All selected migrations have been rolled back successfully in {$totalTime} ms</b>");
        $this->output->line();

        exit(1);
    }

    /**
     * @throws \Exception
     */
    private function checkMigrationsRegistry(): void
    {
        $result = $this->orm->rawSql('SHOW TABLES LIKE "migrations"');

        if ($result === [] && !$this->createMigrationsRegistry()) {
            $this->output->error('Migrations registry table is not found and cannot be created');
            $this->output->line();

            exit(1);
        }
    }

    /**
     * @throws \Exception
     */
    private function createMigrationsRegistry(): bool
    {
        $this->output->info('Creating migrations registry table...');

        $collector = new TableCommandCollector();

        $table = new TableBuilder('migrations', $collector, new SchemaCommandCollector());
        $table->addColumn('id')->int()->autoIncrement()->primary();
        $table->addColumn('version')->string();
        $table->addColumn('checksum')->string(64);
        $table->addColumn('execution_time')->int();
        $table->addColumn('executed_at')->dateTime()->defaultExpression('NOW()');

        $createTableSql = $this->compiler->compile(new CreateTableCommand($table));
        $this->orm->rawSql($createTableSql);

        $createIndexSql = $this->compiler->compile(
            (new CreateIndexCommand('migrations', ['version']))->unique()->name('version_unique_idx'),
        );
        $this->orm->rawSql($createIndexSql);

        return true;
    }
}
