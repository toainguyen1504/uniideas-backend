<?php

namespace App\Repositories\Statistics;

use App\Enum\ReactEnum;
use App\Models\React;
use Illuminate\Support\Facades\DB;

class StatisticsRepository implements StatisticsRepositoryInterface
{
    // public function getDepartmentStats()
    // {
    //     return DB::table('departments as d')
    //         ->leftJoin('users as u', 'u.department_id', '=', 'd.id')
    //         ->leftJoin('ideas as i', 'i.user_id', '=', 'u.id')
    //         ->leftJoin('reacts as r', 'r.idea_id', '=', 'i.id')
    //         ->leftJoin('comments as c', 'c.idea_id', '=', 'i.id')
    //         ->select(
    //             'd.id as department_id',
    //             'd.name as department_name',
    //             DB::raw('COUNT(DISTINCT i.id) as ideas_count'),
    //             DB::raw("SUM(CASE WHEN r.react = ".ReactEnum::LIKE->value." THEN 1 ELSE 0 END) as likes_count"),
    //             DB::raw("SUM(CASE WHEN r.react = ".ReactEnum::DISLIKE->value." THEN 1 ELSE 0 END) as dislikes_count"),
    //             DB::raw('COUNT(DISTINCT c.id) as comments_count')
    //         )
    //         ->groupBy('d.id', 'd.name')
    //         ->get();
    // }

    /**
     * Get statistics by department:
     * - Number of ideas
     * - Number of likes
     * - Number of dislikes
     * - Number of comments
     */
    public function getDepartmentStatistics()
    {
        return DB::table('departments as d')
            ->select('d.id as department_id', 'd.name as department_name')
            ->addSelect([
                'ideas_count' => DB::table('ideas')
                    ->whereIn('user_id', function($query) {
                        $query->select('id')->from('users')->whereColumn('department_id', 'd.id');
                    })->selectRaw('count(*)'),

                'likes_count' => DB::table('reacts')
                    ->whereIn('idea_id', function($query) {
                        $query->select('id')->from('ideas')
                            ->whereIn('user_id', function ($q) {
                                $q->select('id')->from('users')->whereColumn('department_id', 'd.id');
                            });
                    })
                    ->where('react', ReactEnum::LIKE->value)
                    ->selectRaw('count(*)'),

                'dislikes_count' => DB::table('reacts')
                    ->whereIn('idea_id', function($query) {
                        $query->select('id')->from('ideas')
                            ->whereIn('user_id', function ($q) {
                                $q->select('id')->from('users')->whereColumn('department_id', 'd.id');
                            });
                    })
                    ->where('react', ReactEnum::DISLIKE->value)
                    ->selectRaw('count(*)'),

                'comments_count' => DB::table('comments')
                    ->whereIn('idea_id', function($query) {
                        $query->select('id')->from('ideas')
                            ->whereIn('user_id', function ($q) {
                                $q->select('id')->from('users')->whereColumn('department_id', 'd.id');
                            });
                    })->selectRaw('count(*)'),
            ])->get();
    }
}
