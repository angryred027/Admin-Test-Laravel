<?php

use App\Library\Breadcrumbs\BreadcrumbsLibrary;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/* Breadcrumbs::for('site.top', function (BreadcrumbTrail $trail) {
    $trail->push('TOPページ', route('site.top'));
}); */

// TOP 第1階層
/* Breadcrumbs::for('admin.home', function (BreadcrumbTrail $trail, ?int $id = null) {
    $trail->push('HOME', route('admin.home'));
    foreach(config('breadcrumbs.routes')['list'] as $item) {
        BreadcrumbsLibrary::push($item, 'admin.home');
    }
}); */

// Breadcrumbs::macro('getResource', function (BreadcrumbTrail $trail, array $requestParam = []) {
    Breadcrumbs::macro('getResource', function (?string $routeName, array $requestParam = []) {
    // $trail->push('HOME', route('admin.home'));
    /* foreach(config('breadcrumbs.routes') as $item) {
        // BreadcrumbsLibrary::push($item, 'admin.home');
        BreadcrumbsLibrary::push($item, 'admin.home');
    } */
    BreadcrumbsLibrary::push(config('breadcrumbs.routes')['admin.home']);
});
