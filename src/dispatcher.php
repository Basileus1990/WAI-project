<?php
const REDIRECT = 'redirect:';

function dispatch($routing, $userAction)
{
    if (key_exists($userAction, $routing)) {
        $model = [];

        // the controller function should return a viewName name
        $viewName = $routing[$userAction]($model);
        buildResponse($viewName, $model);
    } else {
        buildResponse(ERROR404, []);
    }
}

function buildResponse($viewName, $model)
{
    if (strpos($viewName, REDIRECT) === 0) {
        header("Location: " . substr($viewName, strlen(REDIRECT)));
        exit;
    }
    render($viewName, $model);
}

function render($viewName, $model)
{
    extract($model);
    include 'views/' . $viewName . '.php';
}
