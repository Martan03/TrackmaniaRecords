<?php
mb_internal_encoding('UTF-8');

function autoloadFunction(string $class) : void
{
    if (preg_match("/Presenter$/", $class))
        require("presenter/" . $class . ".php");
    else
        require("model/" . $class . ".php");
}

spl_autoload_register("autoloadFunction");

session_start();

$router = new RouterPresenter();
$router->process(array($_SERVER['REQUEST_URI']));

$router->writeView();