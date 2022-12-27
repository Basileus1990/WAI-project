<?php

function getDB()
{
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai",
        [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]
    );

    return $mongo->wai;
}

function saveSentImageInDatabase($id, $name, $title, $author, $imagesPath)
{
    $db = getDB();
    $dbImage = [
        '_id' => $id,
        'name' => $name,
        'title' => $title,
        'author' => $author,
        'imagesPath' => $imagesPath
    ];

    $db->images->insertOne($dbImage);
}

function getSavedImagesByPage($selectedPage, $imagesPerPage)
{
    $db = getDB();
    $options = [
        'skip' => ($selectedPage - 1) * $imagesPerPage,
        'limit' => $imagesPerPage
    ];
    return $db->images->find([], $options);
}

function getAllImages()
{
    $db = getDB();
    return $db->images->find();
}
