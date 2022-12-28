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
    define('IMAGES_DATA', 'images-data');
    define('UPLOAD_DIR', '/var/www/dev/src/web/images');
    define('IMAGES_PER_PAGE', 5);
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        require 'application-logic/database.php';
        $page = null;
        if (empty($_GET['page'])) {
            $page = 1;
        } elseif ($_GET['page'] > 0 && $_GET['page'] <= ceil(getAmountOfImages() / IMAGES_PER_PAGE)) {
            $page = $_GET['page'];
        } elseif ($_GET['page'] <= 0) {
            $page = 1;
        } elseif ($_GET['page'] > ceil(getAmountOfImages() / IMAGES_PER_PAGE)) {
            $page = intval(ceil(getAmountOfImages() / IMAGES_PER_PAGE));
        }
        $model['page'] = $page;
        $model[IMAGES_DATA] = getSavedImagesByPage($page, IMAGES_PER_PAGE);
        return GALLERY;
    } elseif ($_FILES[SENT_IMAGE_KEY]['error'] != 0) {
        $model[STATUS_MESSAGE] = 'Nie wybrano żadnego pliku';
        return IMAGE_SENT_RESULT;
    } elseif (!($_FILES[SENT_IMAGE_KEY]['type'] === 'image/jpeg' || $_FILES[SENT_IMAGE_KEY]['type'] === 'image/jpg'  || $_FILES[SENT_IMAGE_KEY]['type'] === 'image/png')) {
        $model[STATUS_MESSAGE] = 'Zły typ pliku';
        return IMAGE_SENT_RESULT;
    } elseif ($_FILES[SENT_IMAGE_KEY]['size'] > 1000000) {
        $model[STATUS_MESSAGE] = 'Obraz za duży. Maksymalny rozmiar to 1MB';
        return IMAGE_SENT_RESULT;
    }

    $imageID = new MongoDB\BSON\ObjectID();
    require 'application-logic/imageManipulation.php';
    $imageManipulator = new SentImage($imageID, $_FILES[SENT_IMAGE_KEY], UPLOAD_DIR);
    if (!$imageManipulator->saveOriginalImage()) {
        $model[STATUS_MESSAGE] = 'Przesłanie obrazu się nie powiodło';
        return IMAGE_SENT_RESULT;
    }

    $imageManipulator->createWatermark($_POST['watermark']);
    $imageManipulator->createThumbnail();

    require 'application-logic/database.php';
    saveSentImageInDatabase($imageID, $imageManipulator->image['name'], $_POST['title'], $_POST['author'], UPLOAD_DIR);

    $model[STATUS_MESSAGE] = 'Przesłanie obrazu się powiodło';
    return IMAGE_SENT_RESULT;
}
