<?php

declare(strict_types=1);

namespace App\Trait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

trait ProcessingRoleDataTrait
{
    /**
     * processing role collection
     * @param Illuminate\Support\Collection $collection
     * @return array
     */
    public function processingRoleCollection(Collection $collection): array
    {
        $data = [];
        $roles = $collection->toArray();
        // 一意のロールIDの配列を取得
        $roleIds = array_values($collection->pluck('id')->unique()->toArray());

        foreach ($roleIds as $id) {
            // パーミッションをまとめて重複の無い状態でロールをまとめる
            $result = array_values(array_filter($roles, function ($role) use ($id) {
                return $role->id === $id;
            }));

            if (count($result) > 0) {
                $permissions = array_map(
                    function ($role) {
                        return [
                            'id'   => $role->permissionId,
                            'name' => $role->shortName
                        ];
                    },
                    $result
                );

                $data[] = [
                    'id'          => $result[0]->id,
                    'name'        => $result[0]->name,
                    'code'        => $result[0]->code,
                    'detail'      => $result[0]->detail ?? '',
                    'permissions' => $permissions,
                ];
            }
        }

        return $data;
    }

    /**
     * processing role`s persmissions
     * @param array $ids
     * @param array $permissions
     * @param array $permissionIds
     * @return string
     */
    public function processingPermissions(array $ids, array $permissions, array $permissionIds): string
    {
        $data = array_map(function ($id) use ($permissions, $permissionIds) {
            return $permissions[array_search((int)$id, $permissionIds, true)]->name;
        }, $ids);

        return implode(',', $data);
    }
}
