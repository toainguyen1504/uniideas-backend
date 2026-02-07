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
     * Store a newly created resource in storage.
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
     * Update the specified resource in storage.
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
