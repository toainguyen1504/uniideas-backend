<?php

namespace App\Services;

use App\Models\Submission;
use Illuminate\Support\Str;
use ZipArchive;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;

class SubmissionService
{
    /**
     * Create a ZIP file containing all media files related to a submission's ideas.
     */
    public function createZipForSubmission(Submission $submission): string
    {
        if (!Storage::disk('public')->exists('temp')) {
            Storage::disk('public')->makeDirectory('temp');
        }
        
        $submissionSlug = Str::slug($submission->name) . '-' . $submission->created_at->timestamp;
        $zipFileName = $submissionSlug . '.zip';

        $zipPath = storage_path('app/public/temp/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $submission->load('ideas.media');

            foreach ($submission->ideas as $idea) {
                foreach ($idea->media as $media) {
                    $filePath = $media->getPath();

                    if (file_exists($filePath)) {
                        $relativeNameInZip = Str::slug($idea->title) . '/' . $media->file_name;
                        $zip->addFile($filePath, $relativeNameInZip);
                    }
                }
            }
            $zip->close();
        }

        return $zipPath;
    }
}
