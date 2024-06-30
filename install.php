<?php

$username = exec('whoami');

$projectsDir = ask("Your projects directory", "/Users/$username/projects");
$managerDir = ask("Your env-establisher directory", "/Users/$username/env-manager");

$env = "PROJECTS_DIRECTORY={$projectsDir}" . PHP_EOL;
$env .= "MANAGER_DIRECTORY={$managerDir}" . PHP_EOL;

file_put_contents('.env', $env);

if (!empty($managerDir) && !file_exists($managerDir)) {
    mkdir($managerDir, 0755, true);

    mkdir($managerDir . '/concrete', 0755);
    mkdir($managerDir . '/backups', 0755);

    file_put_contents($managerDir . '/base.env', '');
    file_put_contents($managerDir . '/concrete/example.env', 'EXAMPLE_VAR=1');

    echo "Fill in the " . $managerDir . '/base.env' . ' with a common variables' . PHP_EOL;
    echo "Created " . $managerDir . '/concrete/example.env' . PHP_EOL;
    echo "Usage: php ee --project=example --service=test" . PHP_EOL . PHP_EOL;
}

echo "Configuration saved." . PHP_EOL;

function ask($question, $default = null) {
    if ($default) {
        $question .= " [$default]";
    }
    $question .= ": ";

    echo $question;

    $input = trim(fgets(STDIN));

    if (empty($input) && $default) {
        return $default;
    }

    return $input;
}