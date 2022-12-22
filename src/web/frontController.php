<?php

require_once '../routing.php';
require_once '../dispatcher.php';
require_once '../controllers.php';

session_start();

$userAction = $_GET['action'];

dispatch($routing, $userAction);
