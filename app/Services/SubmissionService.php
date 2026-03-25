<?php

namespace App\Services;

use App\Models\Submission;
use Illuminate\Support\Str;
use ZipArchive;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SubmissionService
{
    /**
     * Create a ZIP file containing all media files related to a submission's ideas.
     */
    public function createZipForSubmission(Submission $submission): string
    {
        $zipFileName = Str::slug($submission->title) . '-' . now()->timestamp . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipFileName);

        // Create temp if it doesn't exist
        // if (!file_exists(storage_path('app/public/temp'))) {
        //     mkdir(storage_path('app/public/temp'), 0755, true);
        // }

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
