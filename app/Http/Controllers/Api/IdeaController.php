<?php

namespace App\Http\Controllers\Api;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Requests\Idea\StoreIdeaRequest;
use App\Http\Requests\Idea\UpdateIdeaRequest;
use App\Http\Resources\Api\CommentResource;
use App\Http\Resources\Api\IdeaResource;
use App\Models\Idea;
use App\Services\MailService;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Ideas\IdeaRepositoryInterface;
use App\Repositories\React\ReactRepositoryInterface;
use App\Repositories\View\ViewRepositoryInterface;
use App\Services\ViewService;

/**
 * @tags Ideas Management
 */
class IdeaController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected IdeaRepositoryInterface $ideaRepository,
        protected CommentRepositoryInterface $commentRepository,
        protected ReactRepositoryInterface $reactRepository,
        protected ViewService $viewService,
        protected MailService $mailService,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_IDEA_LIST)->only('index', 'show');
        $this->middleware('permission:' . Acl::PERMISSION_IDEA_ADD)->only('store');
        $this->middleware('permission:' . Acl::PERMISSION_IDEA_EDIT)->only('update');
        $this->middleware('permission:' . Acl::PERMISSION_IDEA_DELETE)->only('destroy');
    }

    /**
     * Get Ideas List
     *
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: array<\App\Http\Resources\Api\IdeaResource>,
     *      pagination: array{
     *          current_page: int,
     *          last_page: int,
     *          per_page: int,
     *          total: int
     *      }
     * }
     *
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        $ideas = $this->ideaRepository->serverPaginationFiltering($request->all());

        return $this->okResponse(
            IdeaResource::collection($ideas),
            'Idea list retrieved successfully.'
        );
    }

    /**
     * Create Idea
     *
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\IdeaResource,
     * }
     *
     * @param \App\Http\Requests\Idea\StoreIdeaRequest $request
     */
    public function store(StoreIdeaRequest $request)
    {
        $idea = $this->ideaRepository->create($request->validated());

        return $this->okResponse(
            new IdeaResource($idea),
            'Idea created successfully.'
        );
    }

    /**
     * Show Idea Detail
     *     * Display the specified resource.
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\IdeaResource,
     *      comments: array<\App\Http\Resources\Api\CommentResource>,
     *      comments_count: int,
     *      likes_count: int,
     *      dislikes_count: int,
     * }
     *
     * @param \App\Models\Idea $idea
     */
    public function show(Idea $idea)
    {
        $comments = $this->commentRepository->getCommentsByIdea($idea->id);
        $commentsCount = $this->commentRepository->countCommentsByIdea($idea->id);
        $likesCount = $this->reactRepository->countLikesByIdea($idea->id);
        $dislikesCount = $this->reactRepository->countDislikesByIdea($idea->id);

        if (auth()->check()) {
            $this->viewService->viewIdea(auth()->id(), $idea->id);
        }

        return $this->okResponse([
            new IdeaResource($idea),
                'comments' => CommentResource::collection($comments),
                'comments_count' => $commentsCount,
                'likes_count' => $likesCount,
                'dislikes_count' => $dislikesCount,
            ],
            'Idea details retrieved successfully.'
        );
    }

    /**
     * Edit Idea
     *
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\IdeaResource,
     * }
     *
     * @param \App\Http\Requests\Idea\UpdateIdeaRequest $request
     * @param \App\Models\Idea $idea
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        $idea = $this->ideaRepository->update($idea, $request->validated());

        return $this->okResponse(
            new IdeaResource($idea),
            'Idea updated successfully.'
        );
    }

    /**
     * Delete Idea
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
     * @param \App\Models\Idea $idea
     */
    public function destroy(Idea $idea)
    {
        $deleted = $this->ideaRepository->destroy($idea);

        return $deleted
            ? $this->okResponse([], 'Idea deleted successfully.')
            : $this->errorResponse([], 'Failed to delete idea.', 422);
    }
}
