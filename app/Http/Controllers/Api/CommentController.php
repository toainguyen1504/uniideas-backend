<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\IndexCommentRequest;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Api\CommentResource;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Services\CommentService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

/**
 * @tags Comments Management
 */
class CommentController extends Controller
{
    use ApiResponses;
    public function __construct(
        protected CommentRepositoryInterface $commentRepository,
        protected CommentService $commentService,
    ) {
        //
    }

    /**
     * Create Comment
     * 
     * Store a newly created resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *    message: string,
     *    data: array{},
     * }
     * 
     * @param \App\Http\Requests\Comment\StoreCommentRequest $request
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = $this->commentService->create($request->validated());

        if (!$comment) {
            return $this->errorResponse(
                null,
                'Comments are closed after Final Closure Date.',
                422
            );
        }

        return $this->okResponse(
            new CommentResource($comment),
            'Comment created successfully.'
        );
    }

    /**
     * Update Comment
     * 
     * Update the specified resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *    message: string,
     *    data: array{},
     * }
     * 
     * @param \App\Http\Requests\Comment\UpdateCommentRequest $request
     * @param \App\Models\Comment $comment
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $updated = $this->commentService->update($comment, $request->validated());

        if (!$updated) {
            return $this->errorResponse(
                null,
                'Submission is read-only. Cannot update comment.',
                422
            );
        }

        return $this->okResponse(
            new CommentResource($updated),
            'Comment updated successfully.'
        );
    }


    /**
     * Delete Comment
     *
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: array{},
     * }
     *
     * @param \App\Models\Comment $comment
     */
    public function destroy(Comment $comment)
    {
        return $this->commentRepository->destroy($comment)
            ? $this->okResponse([], 'Comment deleted successfully.')
            : $this->errorResponse([], 'Failed to delete comment.', 422);
    }

    /**
     * List Comments
     *
     * Get a paginated list of comments with filter.
     *
     * @authenticated
     *
     * @response array{
     *    message: string,
     *    data: array{},
     *    pagination: array{}
     * }
     *
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'latest');

        $comments = $this->commentRepository->serverPaginationFiltering([
            'filter' => $filter,
            'per_page' => 5
        ]);

        return $this->okResponse([
            'data' => CommentResource::collection($comments),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'last_page'    => $comments->lastPage(),
                'per_page'     => $comments->perPage(),
                'total'        => $comments->total(),
            ]
        ], 'Comment list retrieved successfully.');
    }
}
