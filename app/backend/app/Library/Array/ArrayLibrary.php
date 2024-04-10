<?php

declare(strict_types=1);

namespace App\Library\Array;

use stdClass;
use App\Exceptions\MyApplicationHttpException;
use App\Library\Message\StatusCodeMessages;

class ArrayLibrary
{
    /**
     * convert stdClass (& stdClasses in array) to array
     *
     * @param array<int|string, mixed>|stdClass $object
     * @return array converted value
     */
    public static function toArray(stdClass|array $object): array
    {
        $value = json_decode(json_encode($object), true);

        if (!is_array($value)) {
            throw new MyApplicationHttpException(
                StatusCodeMessages::STATUS_500,
                'failed converting to array.'
            );
        }

        return $value;
    }

    /**
     * get array item first index value.
     *
     * @param array $items
     * @return array<int|string, mixed>|stdClass first index value
     */
    public static function getFirst(array $items): array|stdClass
    {
        return current($items);
    }

    /**
     * paging array elements.
     *
     * @param array $items
     * @param int $page
     * @param ?int $limit
     * @return array
     */
    public static function paging(array $items, int $page, ?int $limit): array
    {
        // 切り捨てでoffset作成
        $offset = is_null($limit) ? 0 : (int)floor($page * $limit);
        return array_slice($items, $offset, $limit);
    }

    /**
     * sort array elements. for Associative array(連想配列).
     *
     * @param array $items
     * @param string $key
     * @param int $order
     * @return array
     */
    public static function sort(array $items, string $key = 'id', int $order = SORT_ASC): array
    {
        $sortValues = [];
        foreach ($items as $item) {
            $sortValues[] = $item[$key];
        }

        array_multisort($sortValues, $order, $items);
        return $items;
    }
}
