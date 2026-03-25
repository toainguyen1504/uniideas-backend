<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DownloadZipController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService,
    ) {
        //
    }

    public function download(Submission $submission)
    {
        try {
            $zipPath = $this->submissionService->createZipForSubmission($submission);

            if (!file_exists($zipPath)) {
                return back()->with('error', 'Could not create zip file.');
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error("Zip Download Failed: " . $e->getMessage());
            return back()->with('error', 'An error occurred while generating the zip.');
        }
    }
}
