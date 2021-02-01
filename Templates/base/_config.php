<?php

use Templates\base\ArchiveTemplate;
use Templates\base\IndexTemplate;
use Templates\base\PageTemplate;
use Templates\base\PostTemplate;
use Templates\base\PublicTemplate;
use Templates\base\SearchTemplate;

return [
    "build" => [
        "builder" => [
            IndexTemplate::class,
            ArchiveTemplate::class,
            PostTemplate::class,
            PageTemplate::class,
            SearchTemplate::class,
            PublicTemplate::class
        ]
    ],
    "homepageMenu" => [
        [
            "url" => "/archives.html",
            "title" => "文章"
        ],
        [
            "url" => "/about",
            "title" => "关于"
        ]
    ],

    "enableSearch" => true,
    "navbar" => [
        [
            "url" => "/",
            "title" => "主页"
        ],
        [
            "url" => "/archives.html",
            "title" => "文章"
        ],
        [
            "url" => "/about",
            "title" => "关于"
        ],
        [
            "url" => "/search.html",
            "title" => "搜索"
        ]
    ],
    "archivesSort" => "desc"
];
