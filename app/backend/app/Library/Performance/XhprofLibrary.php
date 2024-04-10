<?php

declare(strict_types=1);

namespace App\Library\Performance;

class XhprofLibrary
{
    /**
     * set Xhprof File to Storage by XhprofData.
     *
     * @param mixed $xhprofData return of xhprof_disable().
     * @return void
     */
    public static function setXhprofFileByXhprofData(mixed $xhprofData): void
    {
        $path = storage_path('/xhprof');
        include_once "$path/xhprof_lib/utils/xhprof_lib.php";
        include_once "$path/xhprof_lib/utils/xhprof_runs.php";
        # Remove Commet Out.
        // $xhprofRuns = new XHProfRuns_Default();
        // $runId = $xhprofRuns->save_run($xhprofData, 'run_name');
        // file_put_contents("$path/$runId.run_name.xhprof", serialize($xhprofData));
    }
}
