<?php

declare(strict_types=1);

namespace App\Library\Breadcrumbs;

use stdClass;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

class BreadcrumbsLibrary
{
    /**
     * push breadcrumbs setting.
     *
     * @param array $item
     * @param string $parentRouteName
     * @return void
     * @see config/breadcrumbs.php
     */
    public static function push(array $item, string $parentRouteName): void
    {
        Breadcrumbs::for($item['name'], function (BreadcrumbTrail $trail, ?int $id) use ($item, $parentRouteName) {
            $trail->parent($parentRouteName);
            $trail->push(
                $item['title'],
                route(
                    $item['name'],
                    $item['hasParam'] ? ['id' => $id] : [], false
                )
            );

            // 子設定がある場合は再起的に設定する
            if (!empty($item['list'])) {
                foreach($item['list'] as $childItem) {
                    self::push($childItem, $item['name']);
                }
            }
        });
    }
}
