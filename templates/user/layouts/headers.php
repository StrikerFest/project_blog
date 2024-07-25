<?php

use inc\helpers\Common;

/**
 * @var mixed $args
 */

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $args['title'] ?? '' ?></title>
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/style.css') ?>">
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/user/post.css') ?>">
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/user/post-detail.css') ?>">
    <link rel="stylesheet" href="<?= Common::getAssetPath('css/user/comment.css') ?>">
</head>

