<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Http\Requests\Submission\StoreSubmissionRequest;
use App\Http\Requests\Submission\UpdateSubmissionRequest;
use App\Http\Resources\Api\SubmissionResource;
use App\Models\Submission;
use App\Repositories\Submission\SubmissionRepositoryInterface;
use App\Acl\Acl;

/**
 * @tags Submissions Management
 */
class SubmissionController extends Controller
{
    use ApiResponses;

    public function __construct(
        protected SubmissionRepositoryInterface $submissionRepository,
    ) {
        $this->middleware('permission:' . Acl::PERMISSION_SUBMISSION_LIST)->only('index', 'show');
        $this->middleware('permission:' . Acl::PERMISSION_SUBMISSION_ADD)->only('store');
        $this->middleware('permission:' . Acl::PERMISSION_SUBMISSION_EDIT)->only('update');
        $this->middleware('permission:' . Acl::PERMISSION_SUBMISSION_DELETE)->only('destroy'); 
    }

    /**
     * Get Submission List
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

        $submissions = $this->submissionRepository->serverPaginationFiltering($request->all());

        if (!$submissions) {
            return $this->errorResponse(
                [],
                'No submission found.',
                404
            );
        }
            return $this->okResponse(
                SubmissionResource::collection($submissions),
              'submission list retrieved successfully. '
            );
        }


    /**
     * Create Submission
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource,
     * }
     * 
     * @param \App\Http\Requests\Submission\StoreSubmissionRequest $request
     */
    public function store(StoreSubmissionRequest $request)
    {
        $submissions = $this->submissionRepository->create($request->validated());
        
        return $submissions
            ? $this->okResponse(new SubmissionResource($submissions), 'submission created successfully.')
            : $this->errorResponse([], 'Failed to create submission.', 422);
    }

    /**
     * Show Submission Detail
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource,
     * }
     */
      public function show(Submission $submission)
    {
        return $this->okResponse(new SubmissionResource($submission), 'submission details retrieved successfully.');
    }


    /**
     * Edit Submission
     * @response array{
     *   message: string,
     *   data: \App\Http\Resources\Api\SubmissionResource,
     * }
     */
   public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        $submission = $this->submissionRepository->update($submission, $request->validated());
        
        return $submission
            ? $this->okResponse(new SubmissionResource($submission), 'submission updated successfully.')
            : $this->errorResponse([], 'Failed to submission user.', 422);
    }

    /**
     * Delete Submission
     * @response array{
     *   message: string,
     *   data: array{},
     * }
     */
       public function destroy(Submission $submission)
    {
        $deleted = $this->submissionRepository->destroy($submission);

        return $deleted
            ? $this->okResponse([], 'submission deleted successfully.')
            : $this->errorResponse([], 'Failed to delete submission.', 422);
    }
}
