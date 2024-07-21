<?php

namespace App;

class Runtime
{
    protected array $variables = [];
    private string $runtimePath;
    public static function init(array $config): self
    {
        $service  = new self();
        $service->runtimePath = $config['runtimeDirectory'] . '/runtime.json';
        $service->checkDirs($config);

        $service->variables = json_decode(file_get_contents($service->runtimePath), true);

        return $service;
    }

    public function get($name)
    {
        return $this->variables[$name] ?? null;
    }

    public function set($name, $value): void
    {
        $this->variables[$name] = $value;
        $this->update();
    }

    protected function update(): void
    {
        file_put_contents($this->runtimePath, json_encode($this->variables, JSON_PRETTY_PRINT));
    }

    private function checkDirs(array $config): void
    {
        if (!file_exists($config['runtimeDirectory'] . '/runtime.json')) {
            if (!file_exists($config['runtimeDirectory'])) {
                mkdir($config['runtimeDirectory'], 0755, true);
            }
            file_put_contents($config['runtimeDirectory'] . '/runtime.json', '{}', FILE_APPEND);
        }
    }
}