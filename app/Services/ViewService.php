<?php

namespace App\Services;

use App\Models\View;
use App\Repositories\View\ViewRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Idea;

class ViewService
{
    public function __construct(
        protected ViewRepositoryInterface $viewRepository,
    ) {
        //
    }

    /**
     * Create view record if not exists
     */
    public function viewIdea(int $userId, int $ideaId): View
    {
        return DB::transaction(function () use ($userId, $ideaId) {
            $view = View::firstOrCreate(
                ['user_id' => $userId, 'idea_id' => $ideaId],
                ['visit_time' => now()]
            );

            if ($view->wasRecentlyCreated) {
                Idea::where('id', $ideaId)->increment('total_views');
            }

            return $view;
        });
    }
}
