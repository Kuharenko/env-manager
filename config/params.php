<?php

$params = [
    'projectDirectory' => $_ENV['PROJECTS_DIRECTORY'] ?? '',
    'managerDirectory' => $_ENV['MANAGER_DIRECTORY'] ?? '',
];

$params['concreteManagerDirectory'] = $params['managerDirectory'] . '/concrete';
$params['runtimeDirectory'] = $params['managerDirectory'] . '/runtime';

return $params;