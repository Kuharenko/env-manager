<?php

namespace App;
const GREEN_COLOR = "\033[32m";
const RED_COLOR = "\033[31m";
const DEFAULT_COLOR = "\033[0m";
class EnvReplacer
{
    private string $projectDir = '';
    private string $managerDir = '';
    private string $concreteDir = '';
    private string $backupDir = '';
    private string $currentDate = '';
    private bool $backupEnabled = true;
    private string $envResult = '';

    public function __construct(array $config = [])
    {
        $this->init($config);
    }

    public function run($targetEnv, $targetServices = null, $restore = false): void
    {
        if (!$restore) {
            $this->generateResult($targetEnv);
        }

        foreach (scandir($this->projectDir) as $project) {
            if ($this->isCreditorroProject($project, $targetServices)) {
                $envPath = $this->projectDir . '/' . $project . '/.env';

                if ($restore) {
                    $this->restore($envPath, $project);
                    continue;
                }

                if ($targetEnv) {
                    if (file_exists($envPath)) {
                        $this->backup($envPath, $project);
                    }

                    file_put_contents($envPath, $this->envResult);
                    $this->showMessage('.env was configured with "' . $targetEnv . '" parameters for: ' . $project);
                }
            }
        }
    }

    protected function restore(string $envPath, string $project): void
    {
        $backupPath = $this->backupDir . '/' . $project;

        if (file_exists($backupPath)) {
            $files = scandir($backupPath, SCANDIR_SORT_DESCENDING);
            $latestBackupPath = $files[0] ?? false;

            if ($latestBackupPath && $latestBackupPath !== '.' && $latestBackupPath !== '..') {
                rename($backupPath . '/' . $latestBackupPath, $envPath);
                $this->showMessage('.env was restored for: ' . $project);
            }
        }
    }

    protected function generateResult(string $targetEnv): void
    {
        $baseParams = $this->getBaseContent();
        $targetParams = $this->getTargetContent($targetEnv);

        $this->envResult = $baseParams . PHP_EOL . PHP_EOL . $targetParams;
    }

    protected function getBaseContent(): string
    {
        $basePath = $this->managerDir . '/' . 'base.env';
        if (!file_exists($basePath)) {
            $this->showError('Base ENV is not defined: ' . $basePath);
            die();
        }

        return file_get_contents($basePath);
    }

    protected function getTargetContent($targetEnv): string
    {
        if (!$targetEnv) {
            return '';
        }

        $targetPath = $this->concreteDir . '/' . $targetEnv . '.env';
        if (!file_exists($targetPath)) {
            $this->showError("ENV for \"'" . $targetEnv . "'\" is not defined: '" . $targetPath);
            die();
        }

        return file_get_contents($targetPath);
    }

    protected function backup(string $envPath, string $project): void
    {
        if ($this->backupEnabled) {
            $backupPath = $this->backupDir . '/' . $project;
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0777, true);
            }

            rename($envPath, $backupPath . '/' . $this->currentDate . '.env');
        } else {
            unlink($envPath);
        }
    }

    protected function isCreditorroProject($project, $targetServices): bool
    {
        if ($project != '.' && $project != '..' && is_dir($this->projectDir . '/' . $project)) {
            if ($targetServices) {
                $services = explode(',', $targetServices);
                foreach ($services as $service) {
                    if (!str_contains($project, $service)) {
                        return false;
                    }
                }
            }

            $path = 'cd ' . $this->projectDir . '/' . $project;
            $gitRemote = 'git remote -v';

            $git = exec($path . ' && ' . $gitRemote);

            if (str_contains($git, 'creditexpress.com')) {
                return true;
            }
        }

        return false;
    }

    private function init(array $config = []): void
    {
        $this->projectDir = $config['projectDirectory'];
        $this->managerDir = $config['managerDirectory'];
        $this->concreteDir = $config['concreteManagerDirectory'];

        $this->backupDir = $this->managerDir . '/backups';
        $this->currentDate = date('Y-m-d H.i');

        $this->checkDirs();
    }

    private function checkDirs(): void
    {
        if (!file_exists($this->projectDir)) {
            $this->showError('"project" path does not exist: ' . $this->projectDir);
            die();
        }

        if (!file_exists($this->managerDir)) {
            $this->showError('"manager" path does not exist: ' . $this->managerDir);
            die();
        }
    }

    private function showError($message): void
    {
        echo RED_COLOR . $message . DEFAULT_COLOR . PHP_EOL;
    }

    private function showMessage($message): void
    {
        echo GREEN_COLOR . $message . DEFAULT_COLOR . PHP_EOL;
    }
}