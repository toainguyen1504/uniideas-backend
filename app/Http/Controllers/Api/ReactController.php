<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\React\IndexReactRequest;
use App\Http\Requests\React\StoreReactRequest;
use App\Http\Requests\React\UpdateReactRequest;
use App\Http\Resources\Api\ReactResource;
use App\Models\React;
use App\Repositories\React\ReactRepositoryInterface;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

/**
 * @tags Reacts Management
 */
class ReactController extends Controller
{
    use ApiResponses;
    public function __construct(
        protected ReactRepositoryInterface $reactRepository,
    ){
        //
    }
    
    /**
     * Create a new react.
     *
     * Store a newly created resource in storage. Fields:
     * - `idea_id` (int, required): ID of the idea being reacted to.
     * - `react` (int, nullable): React type. Use values from `ReactEnum`:
     *     - `0` — Unknown
     *     - `1` — Like
     *     - `2` — Dislike
     * - `is_anonymous` (int, nullable): Whether the react is anonymous. Use values from `AnonymousEnum`:
     *     - `1` — Anonymous
     *     - `2` — Not Anonymous
     * 
     * @authenticated
     * 
     * @response array{
     *     message: string,
     *     data: array{},
     * }
     * 
     * @param \App\Http\Requests\React\StoreReactRequest $request
     */
    public function store(StoreReactRequest $request)
    {
        return $this->reactRepository->create($request->validated())
            ? $this->okResponse([], 'React created successfully.')
            : $this->errorResponse([], 'Failed to create react.', 422);
    }

    /**
     * Update React
     * 
     * Update the specified resource in storage. Fields (same as store):
     * - `idea_id` (int): ID of the idea being reacted to.
     * - `react` (int, nullable): React type. Use values from `ReactEnum`:
     *     - `0` — Unknown
     *     - `1` — Like
     *     - `2` — Dislike
     * - `is_anonymous` (int, nullable): Whether the react is anonymous. Use values from `AnonymousEnum`:
     *     - `1` — Anonymous
     *     - `2` — Not Anonymous
     * 
     * @authenticated
     * 
     * @response array{
     *    message: string,
     *    data: array{},
     * }
     * 
     * @param \App\Http\Requests\React\UpdateReactRequest $request
     * @param \App\Models\React $react
     */
    public function update(UpdateReactRequest $request, React $react)
    {
        return $this->reactRepository->update($react, $request->validated())
            ? $this->okResponse([], 'React updated successfully.')
            : $this->errorResponse([], 'Failed to update react.', 422);
    }

    /**
     * Delete React
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
     * @param \App\Models\React $react
     */
    public function destroy(React $react)
    {
        $deleted = $this->reactRepository->destroy($react);

        return $deleted
            ? $this->okResponse([], 'React deleted successfully.')
            : $this->errorResponse([], 'Failed to delete react.', 422);
    }
}
