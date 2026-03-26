<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Services\SubmissionService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DownloadZipController extends Controller
{
    use ApiResponses;
    public function __construct(
        protected SubmissionService $submissionService,
    ) {
        //
    }

    /**
     * Submission Zip
     * 
     * Zip and download all media files related to a submission's ideas
     * 
     * @param Submission $submission
     */
    public function downloadSubmission(Submission $submission)
    {
        set_time_limit(300);

        try {
            $zipPath = $this->submissionService->createZipForSubmission($submission);

            Log::info("Zip path generated for Submission {$submission->id}: {$zipPath}");
            if (!File::exists($zipPath)) {
                Log::error("Zip file does not exist for Submission {$submission->id}: {$zipPath}");
                return $this->errorResponse('Zip file could not be created.', 500);
            }

            $fileName = basename($zipPath);
            $size = File::size($zipPath);
            Log::info("Zip ready for download for Submission {$submission->id}: {$fileName} ({$size} bytes)");

            return response()->download($zipPath, $fileName, [
                'Content-Type' => 'application/zip',
                'Access-Control-Expose-Headers' => 'Content-Disposition'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error("Zip Download Failed for Submission ID {$submission->id}: " . $e->getMessage());  
            return $this->errorResponse('An error occurred while generating the zip.', 500);
        }
    }
}
