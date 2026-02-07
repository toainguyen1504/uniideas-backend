<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\IndexCommentRequest;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
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
        return $this->commentRepository->create($request->validated()) 
            ? $this->okResponse([], 'Comment created successfully.')
            : $this->errorResponse([], 'Failed to create comment.', 422);
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
        return $this->commentRepository->update($comment, $request->validated()) 
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
