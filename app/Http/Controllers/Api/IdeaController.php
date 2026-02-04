<?php

namespace App\Http\Controllers\Api;

use App\Acl\Acl;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Requests\Idea\StoreIdeaRequest;
use App\Http\Requests\Idea\UpdateIdeaRequest;
use App\Http\Resources\Api\IdeaResource;
use App\Models\Idea;
use App\Repositories\Ideas\IdeaRepositoryInterface;

/**
 * @tags Ideas Management
 */
class IdeaController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected IdeaRepositoryInterface $ideaRepository,
    ) {
        
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
        $idea = $this->ideaRepository->create($request->validated(), $request->file('file'));

        return $this->okResponse(
            new IdeaResource($idea),
            'Idea created successfully.'
        );
    }

    /**
     * Show Idea Detail
     *
     * Display the specified resource.
     *
     * @authenticated
     *
     * @response array{
     *      message: string,
     *      data: \App\Http\Resources\Api\IdeaResource,
     * }
     *
     * @param \App\Models\Idea $idea
     */
    public function show(Idea $idea)
    {
        return $this->okResponse(
            new IdeaResource($idea),
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
        $idea = $this->ideaRepository->update($idea, $request->validated(), $request->file('file'));

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
