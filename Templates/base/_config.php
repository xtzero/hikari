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
        // [
        //     "url" => "/archives.html",
        //     "title" => "文章"
        // ],
        // [
        //     "url" => "/about",
        //     "title" => "关于"
        // ]
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
            "url" => "/children",
            "title" => "作品站"
        ],
        [
            "url" => "/friends",
            "title" => "友链"
        ],
        [
            "url" => "/ad",
            "title" => "推广"
        ],
        [
            "url" => "/about",
            "title" => "关于"
        ],
        [
            "url" => "/search.html",
            "title" => "🔍"
        ]
    ],
    "archivesSort" => "desc",
    'gitee_repo_url' => 'https://gitee.com/xtzero/blog/tree/hikari/',
    'post_per_page' => 10
];
