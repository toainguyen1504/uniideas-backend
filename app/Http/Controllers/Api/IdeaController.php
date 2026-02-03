<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Models\Idea;
use App\Http\Requests\Idea\StoreIdeaRequest;
use App\Http\Requests\Idea\UpdateIdeaRequest;
use App\Http\Resources\Api\IdeaResource;


/**
 * @tags Ideas Management
 */
class IdeaController extends Controller
{
    use ApiResponses;

    /**
     * Get Idea List
     * 
     * Display a listing of the resource.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\IdeaResource,
     *   pagination: array{
     *     current_page: int,
     *     last_page: int,
     *     per_page: int,
     *     total: int
     *   }
     * }
     */
    public function index(Request $request)
    {
        $ideas = Idea::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->submission_id, fn($q) => $q->where('submission_id', $request->submission_id))
            ->orderBy('created_at', 'desc')
            ->paginate(5);

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
     *   message: string,
     *   data: \App\Http\Resources\Api\IdeaResource,
     * }
     */
    public function store(StoreIdeaRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('uploads');
        }

        $idea = Idea::create($data);

        return $idea
            ? $this->okResponse(new IdeaResource($idea), 'Idea created successfully.')
            : $this->errorResponse([], 'Failed to create idea.', 422);
    }

    /**
     * Show Idea Detail
     * 
     * Display the specified resource.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\IdeaResource,
     * }
     */
    public function show(Idea $idea)
    {
        return $this->okResponse(new IdeaResource($idea), 'Idea details retrieved successfully.');
    }

    /**
     * Edit Idea
     * 
     * Update the specified resource in storage.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\IdeaResource,
     * }
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('uploads');
        }

        $idea->update($data);

        return $this->okResponse(new IdeaResource($idea), 'Idea updated successfully.');
    }

    /**
     * Delete Idea
     * 
     * Remove the specified resource from storage.
     * 
     * @authenticated
     * 
     * @response array{
     *   message: string,
     *   data: array{},
     * }
     */
    public function destroy(Idea $idea)
    {
        $deleted = $idea->delete();

        return $deleted
            ? $this->okResponse([], 'Idea deleted successfully.')
            : $this->errorResponse([], 'Failed to delete idea.', 422);
    }
}
