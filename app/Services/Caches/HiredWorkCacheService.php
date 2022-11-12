<?php

namespace App\Services\Caches;

use App\Models\Contractor;
use App\Models\HiredWork;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class HiredWorkCacheService
{
    /**
     * @param  Worker  $worker
     * @param  array|string  $with
     * @param  bool  $clear
     * @param  bool  $clearTag
     *
     * @return Collection|null
     */
    public static function fromWorker(
        ?Worker $worker = null,
        array|string $with = '',
        array $filters = [],
        bool $clear = false,
        bool $clearTag = false
    ): ?Collection {
        if ($clearTag) {
            Cache::tags(['worker:hired_works'])->flush();
            return null;
        }

        $withJson = json_encode($with);
        $filtersJson = json_encode($filters);
        $key = "worker({$worker->uuid}):hired_works:with({$withJson}):filters({$filtersJson})";

        if ($clear) {
            Cache::tags(['worker:hired_works'])->forget($key);
            return null;
        }

        return Cache::tags(['worker:hired_works'])->rememberForever($key, function () use (&$worker, &$with, &$filters) {
            $query = HiredWork::filter($filters);

            if (! empty($with)) {
                $query->with($with);
            }

            $query->whereHas('work.worker', function (Builder $query) use (&$worker) {
                $query->whereWorkerId($worker->id);
            });

            return $query->get();
        });
    }

    /**
     * @param  Contractor  $contractor
     * @param  array|string  $with
     * @param  bool  $clear
     * @param  bool  $clearTag
     *
     * @return Collection|null
     */
    public static function fromContractor(
        ?Contractor $contractor = null,
        array|string $with = '',
        array $filters = [],
        bool $clear = false,
        bool $clearTag = false
    ): ?Collection {
        if ($clearTag) {
            Cache::tags(['contractor:hired_works'])->flush();
            return null;
        }

        $withJson = json_encode($with);
        $filtersJson = json_encode($filters);
        $key = "contractor({$contractor->uuid}):hired_works:with({$withJson}):filters({$filtersJson})";

        if ($clear) {
            Cache::tags(['contractor:hired_works'])->forget($key);
            return null;
        }

        return Cache::tags(['contractor:hired_works'])->rememberForever($key, function () use (&$contractor, &$with, &$filters) {
            $query = HiredWork::filter($filters);

            if (! empty($with)) {
                $query->with($with);
            }

            $query->whereContractorId($contractor->id);

            return $query->get();
        });
    }

    /**
     * @param  string|null  $uuid
     * @param  string  $with
     * @param  boolean  $clear
     * @param  boolean  $clearTag
     *
     * @return HiredWork|null
     */
    public static function findUuid(?string $uuid = null, array|string $with = '', bool $clear = false, bool $clearTag = false): ?HiredWork
    {
        if ($clearTag) {
            Cache::tags(['hired_work', 'hired_work:uuid'])->flush();
            return null;
        }

        $key = "hired_work:uuid({$uuid})";

        if ($clear) {
            Cache::tags(['hired_work', 'hired_work:uuid'])->forget($key);
            return null;
        }

        return Cache::tags(['hired_work', 'hired_work:uuid'])->rememberForever($key, function () use (&$uuid, &$with) {
            $query = HiredWork::query();
            if (! empty($with)) {
                $query->with($with);
            }

            return $query->whereUuid($uuid)->first();
        });
    }
}