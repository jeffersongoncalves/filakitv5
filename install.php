#!/usr/bin/env php
<?php

class LaravelInstaller
{
    private string $packageManager;

    private array $validPackageManagers = ['npm', 'pnpm', 'bun', 'yarn'];

    private string $basePath;

    public function __construct(string $basePath = __DIR__)
    {
        $this->basePath = $basePath;
    }

    /**
     * Run the installation process
     */
    public function install(): void
    {
        $this->displayHeader();

        // Initialize the package manager
        $this->setupPackageManager();

        // Install dependencies
        $this->installComposerDependencies();
        $this->setupEnvironmentFile();
        $this->generateAppKey();
        $this->setupDatabase();
        $this->runMigrations();
        $this->runSeeders();
        $this->installNodeDependencies();
        $this->runBuildAssets();

        // Configure Herd if used
        $this->configureHerd();

        $this->displayCompletionMessage();
    }

    private function displayHeader(): void
    {
        echo "Laravel Project Installer\n";
        echo "-------------------------\n";
    }

    /**
     * Set up a package manager based on user selection
     */
    private function setupPackageManager(): void
    {
        $this->packageManager = $this->ask(
            'Select a package manager ['.implode(', ', $this->validPackageManagers).']',
            'npm'
        );

        if (! in_array($this->packageManager, $this->validPackageManagers)) {
            $this->exitWithError('Invalid package manager selected.');
        }
    }

    private function installComposerDependencies(): void
    {
        if (! is_dir('vendor')) {
            $this->runCommand('composer install --no-interaction --ignore-platform-reqs');
        } else {
            $this->logInfo('Vendor directory already exists. Skipping composer install.');
        }
    }

    private function setupEnvironmentFile(): void
    {
        if (! file_exists('.env')) {
            $this->logInfo('Creating .env file...');
            if (! copy('.env.example', '.env')) {
                $this->exitWithError('Failed to create .env file.');
            }
        } else {
            $this->logInfo('.env file already exists. Skipping copy.');
        }
    }

    private function generateAppKey(): void
    {
        $this->runCommand('php artisan key:generate');
    }

    private function setupDatabase(): void
    {
        $dbPath = $this->basePath.'/database/database.sqlite';
        $this->ensureDatabaseFile($dbPath);
    }

    private function runMigrations(): void
    {
        $this->runCommand('php artisan migrate');
    }

    private function runSeeders(): void
    {
        if ($this->confirm('Do you want to run the seeders?')) {
            $this->runCommand('php artisan db:seed');
        } else {
            $this->logInfo('Skipping database seeding.');
        }
    }

    private function installNodeDependencies(): void
    {
        if (! is_dir('node_modules')) {
            $this->runCommand("{$this->packageManager} install");
        } else {
            $this->logInfo("Node modules already installed. Skipping {$this->packageManager} install.");
        }
    }

    private function runBuildAssets(): void
    {
        if (is_dir('node_modules')) {
            $this->runCommand("{$this->packageManager} run build");
        } else {
            $this->logInfo("Node modules is not installed. Skipping {$this->packageManager} run build.");
        }
    }

    private function configureHerd(): void
    {
        $usingHerd = $this->confirm('Are you using Herd?');

        if ($usingHerd) {
            $this->logInfo('Herd detected. Running herd link...');
            $this->runCommand('herd link');

            $secureSite = $this->confirm('Do you want to secure the site with HTTPS?');
            if ($secureSite) {
                $this->logInfo('Securing site with HTTPS...');
                $this->runCommand('herd secure');
            }
        }
    }

    private function displayCompletionMessage(): void
    {
        echo "\n✅ Installation complete!\n";
    }

    private function ensureDatabaseFile(string $path): void
    {
        if (! file_exists($path)) {
            $this->logInfo("Creating database file at: $path");

            if (! is_dir(dirname($path))) {
                if (! mkdir(dirname($path), 0755, true)) {
                    $this->exitWithError('Failed to create directory: '.dirname($path));
                }
            }

            if (! touch($path)) {
                $this->exitWithError("Failed to create database file: $path");
            }
        } else {
            $this->logInfo("Database file already exists: $path");
        }
    }

    private function ask(string $question, string $default = ''): string
    {
        $prompt = $question.($default ? " ({$default})" : '').': ';
        $answer = readline($prompt);

        return $answer ?: $default;
    }

    private function confirm(string $question, bool $default = false): bool
    {
        $answer = $this->ask($question.' ['.($default ? 'Y/n' : 'y/N').']');

        return $answer ? strtolower($answer[0]) === 'y' : $default;
    }

    private function runCommand(string $command): void
    {
        $this->logInfo("Running: $command");
        passthru($command, $exitCode);

        if ($exitCode !== 0) {
            $this->exitWithError("Command failed: $command", $exitCode);
        }
    }

    private function logInfo(string $message): void
    {
        echo "$message\n";
    }

    private function exitWithError(string $message, int $code = 1): void
    {
        echo "ERROR: $message\n";
        exit($code);
    }

    private function isWindows(): bool
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}

(new LaravelInstaller)->install();
