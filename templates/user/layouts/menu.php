<?php

use inc\helpers\Common;

?>
<link rel="stylesheet" href="<?= Common::getAssetPath('css/menu.css') ?>">
<div class="menu-navbar">
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Posts
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="post">View posts</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Categories
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="category">View categories</a>
            <a href="category/create">Create category</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Tags
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <a href="tag">View tags</a>
            <a href="tag/create">Create tag</a>
        </div>
    </div>
    <div class="menu-dropdown">
        <button class="menu-dropbtn">Profile
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="menu-dropdown-content">
            <?php if (strstr($_SERVER['REQUEST_URI'], '/admin')) : ?>
                <a href="/admin/logout">Logout</a>
            <?php else : ?>
                <a href="/logout">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</div>