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
                'Failed to create comment.',
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
        return $this->commentService->update($comment, $request->validated())
            ? $this->okResponse([], 'Comment updated successfully.')
            : $this->errorResponse([], 'Failed to update comment.', 422);
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
}
