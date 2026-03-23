<?php

namespace App\Repositories\Statistics;

use App\Enum\ReactEnum;
use App\Models\React;
use Illuminate\Support\Facades\DB;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    public function getDepartmentStats()
    {
        return DB::table('departments as d')
            ->leftJoin('users as u', 'u.department_id', '=', 'd.id')
            ->leftJoin('ideas as i', 'i.user_id', '=', 'u.id')
            ->leftJoin('reacts as r', 'r.idea_id', '=', 'i.id')
            ->leftJoin('comments as c', 'c.idea_id', '=', 'i.id')
            ->select(
                'd.id as department_id',
                'd.name as department_name',
                DB::raw('COUNT(DISTINCT i.id) as ideas_count'),
                DB::raw("SUM(CASE WHEN r.react = ".ReactEnum::LIKE->value." THEN 1 ELSE 0 END) as likes_count"),
                DB::raw("SUM(CASE WHEN r.react = ".ReactEnum::DISLIKE->value." THEN 1 ELSE 0 END) as dislikes_count"),
                DB::raw('COUNT(DISTINCT c.id) as comments_count')
            )
            ->groupBy('d.id', 'd.name')
            ->get();
    }
}
