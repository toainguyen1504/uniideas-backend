<?php

namespace App\Http\Controllers\Api;

use App\Enum\AnonymousEnum;
use App\Http\Controllers\Controller;
use App\Models\Idea;
use App\Models\Submission;
use App\Services\ExportService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected ExportService $exportService,
    ) {
        //
    }

    /**
     * Export Ideas
     * 
     * Export Ideas to Excel based on Submission
     * 
     * @param Submission $submission
     */
    public function exportIdeasBySubmission(Submission $submission)
    {
        $ideas = Idea::where('submission_id', $submission->id)
            ->with(['user:id,name', 'category:id,name'])
            ->get()
            ->map(function ($idea) use ($submission) {
                return [
                    'title' => $idea->title,
                    'slug' => $idea->slug,
                    'content' => strip_tags($idea->content),
                    'status' => $idea->status->value,
                    'is_anonymous' => $idea->is_anonymous ? AnonymousEnum::ANONYMOUS->value : AnonymousEnum::NOT_ANONYMOUS->value,
                    'total_views'    => $idea->total_views,
                    'total_comments' => $idea->total_comments,
                    'user' => $idea->user->name ?? 'N/A',
                    'category' => $idea->category->name ?? 'N/A',
                    'submission' => $submission->name,
                ];
            });

        $headings = [
            'Title', 'Slug', 'Content', 'Status', 
            'Anonymous', 'Views', 'Comments', 
            'User', 'Category', 'Submission'
        ];

        $filename = 'ideas-' . Str::slug($submission->name) . '-' . now()->format('YmdHis') . '.xlsx';

        return $this->exportService->exportData($ideas, $headings, $filename);
    }
}
