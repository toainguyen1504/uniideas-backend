<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Requests\Submission\StoreSubmissionRequest;
use App\Http\Requests\Submission\UpdateSubmissionRequest;
use App\Http\Requests\Submission\DeleteSubmissionRequest;
use App\Http\Resources\Api\SubmissionResource;
use App\Models\Submission;
use App\Repositories\Submission\SubmissionRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * @tags Submissions Management
 */
class SubmissionController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected SubmissionRepositoryInterface $submissionRepository,
    ) {
        // Không có middleware (giống CategoryController)
    }

    /**
     * Get Submission List
     * 
     * Display a listing of the resource.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource[],
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
        try {
            $submissions = $this->submissionRepository->serverPaginationFiltering($request->all());
            
            if (!$submissions || $submissions->isEmpty()) {
                return $this->errorResponse(
                    [],
                    'No submissions found.',
                    404
                );
            }

            return $this->okResponse(
                SubmissionResource::collection($submissions),
                'Submission list retrieved successfully.'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                ['error' => $e->getMessage()],
                'Failed to retrieve submissions.',
                500
            );
        }
    }

    /**
     * Create Submission
     * 
     * Store a newly created resource in storage.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource,
     * }
     * 
     * @param \App\Http\Requests\Submission\StoreSubmissionRequest $request
     */
    public function store(StoreSubmissionRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $submission = $this->submissionRepository->create($request->validated());
            
            DB::commit();
            
            return $this->okResponse(
                new SubmissionResource($submission),
                'Submission created successfully.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->errorResponse(
                ['error' => $e->getMessage()],
                'Failed to create submission.',
                422
            );
        }
    }

    /**
     * Show Submission Detail
     * 
     * Display the specified resource.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource,
     * }
     */
    public function show(Submission $submission)
    {
        try {
            return $this->okResponse(
                new SubmissionResource($submission->load('ideas')),
                'Submission details retrieved successfully.'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                ['error' => $e->getMessage()],
                'Failed to retrieve submission details.',
                500
            );
        }
    }

    /**
     * Edit Submission
     * 
     * Update the specified resource in storage.
     * 
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource,
     * }
     */
    public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        try {
            // Kiểm tra nếu submission đã finally closed
            if ($submission->is_final_closed) {
                return $this->errorResponse(
                    [],
                    'Cannot update a finally closed submission.',
                    403
                );
            }
            
            DB::beginTransaction();
            
            $updatedSubmission = $this->submissionRepository->update($submission, $request->validated());
            
            DB::commit();
            
            return $this->okResponse(
                new SubmissionResource($updatedSubmission),
                'Submission updated successfully.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->errorResponse(
                ['error' => $e->getMessage()],
                'Failed to update submission.',
                422
            );
        }
    }

    /**
     * Delete Submission
     * 
     * Remove the specified resource from storage.
     * 
     * @response array{
     *   message: string,
     *   data: array{},
     * }
     */
    public function destroy(DeleteSubmissionRequest $request, Submission $submission)
    {
        try {
            DB::beginTransaction();
            
            $deleted = $this->submissionRepository->destroy($submission->id);
            
            if (!$deleted) {
                return $this->errorResponse(
                    [],
                    'Failed to delete submission.',
                    422
                );
            }
            
            DB::commit();
            
            return $this->okResponse(
                [],
                'Submission deleted successfully.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->errorResponse(
                ['error' => $e->getMessage()],
                'Failed to delete submission.',
                422
            );
        }
    }
}