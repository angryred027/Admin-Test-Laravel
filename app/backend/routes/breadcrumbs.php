<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/* Breadcrumbs::for('site.top', function (BreadcrumbTrail $trail) {
    $trail->push('TOPページ', route('site.top'));
}); */

// TOP 第1階層
Breadcrumbs::for('admin.home', function (BreadcrumbTrail $trail, ?int $id) {
    $trail->push('HOME', route('admin.home'));
    foreach(config('breadcrumbs.routes')['list'] as $item) {
        Breadcrumbs::for($item['name'], function (BreadcrumbTrail $trail, ?int $id) use ($item) {
            $trail->parent('admin.home');
            $trail->push(
                $item['title'],
                route(
                    $item['name'],
                    $item['hasParam'] ? ['id' => $id] : [], false
                )
            );
            if (!empty($item['list'])) {
                foreach($item['list'] as $child) {
                    Breadcrumbs::for($child['name'], function (BreadcrumbTrail $trail, ?int $id) use ($item, $child) {
                        $trail->parent($item['name']);
                        $trail->push(
                            $child['title'],
                            route(
                                $child['name'],
                                $child['hasParam'] ? ['id' => $id] : [], false
                            )
                        );
                    });
                }
            }
        });
    }
});
