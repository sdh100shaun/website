<?php
// Application middleware


$app->add($container->get('csrf'));

$app->add($container->get('auth.middleware'));

$app->add(new App\Middleware\CliRequest());
