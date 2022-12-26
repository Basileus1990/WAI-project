<?php

function index(&$model)
{
    return INDEX;
}

function contact(&$model)
{
    return CONTACT;
}

function goalTracker(&$model)
{
    return GOALTRACKER;
}

function gallery(&$model)
{
    define('SENT_IMAGE_KEY', 'sent-image');
    define('STATUS_MESSAGE', 'image-status-message');
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        return GALLERY;
    } elseif ($_FILES[SENT_IMAGE_KEY]['error'] != 0) {
        $model[STATUS_MESSAGE] = 'Nie wybrano żadnego pliku';
        return GALLERY;
    } elseif (!($_FILES[SENT_IMAGE_KEY]['type'] === 'image/jpeg' || $_FILES[SENT_IMAGE_KEY]['type'] === 'image/jpg'  || $_FILES[SENT_IMAGE_KEY]['type'] === 'image/png')) {
        $model[STATUS_MESSAGE] = 'Zły typ pliku';
        return GALLERY;
    } elseif ($_FILES[SENT_IMAGE_KEY]['size'] > 1000000) {
        $model[STATUS_MESSAGE] = 'Obraz za duży. Maksymalny rozmiar to 1MB';
        return GALLERY;
    }

    require 'application-logic/imageManipulation.php';
    if (!manipulateSentImage($_FILES[SENT_IMAGE_KEY], 'test')) {
        $model[STATUS_MESSAGE] = 'Przesławnie obrazu się nie powiodło';
        return GALLERY;
    }


    $model[STATUS_MESSAGE] = 'Przesłanie obrazu się powiodło';
    return GALLERY;
}
