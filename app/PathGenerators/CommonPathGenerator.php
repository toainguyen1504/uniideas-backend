<?php

namespace App\PathGenerators;

use App\Models\Idea;
use App\Models\Submission;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CommonPathGenerator implements PathGenerator
{
    /**
     * Original path for the uploaded file
     */
    public function getPath(Media $media): string
    {
        return match (true) {
            $media->model instanceof Idea => $this->getIdeaPath($media->model),
            $media->model instanceof Submission => $this->getSubmissionPath($media->model),
            default => $this->getDefaultPath($media),
        };
    }

    /**
     * Logic for Idea: {submission-slug-timestamp}/{idea-slug-timestamp}/
     */
    protected function getIdeaPath(Idea $idea): string
    {
        $sub = $idea->submission;
        
        $subFolder = Str::slug($sub->name) . '-' . ($sub->created_at?->timestamp ?? now()->timestamp);
        $ideaFolder = ($idea->slug) . '-' . ($idea->created_at?->timestamp ?? now()->timestamp);
        
        return "{$subFolder}/{$ideaFolder}/";
    }

    /**
     * Logic for Submission: submissions/{submission-slug-timestamp}/
     */
    protected function getSubmissionPath(Submission $submission): string
    {
        $slug = Str::slug($submission->title ?? 'submission');
        $time = $submission->created_at?->timestamp ?? now()->timestamp;

        return "submissions/{$slug}-{$time}/";
    }

    /**
     * Logic default path: {model_name}/
     */
    protected function getDefaultPath(Media $media): string
    {
        return Str::lower(class_basename($media->model_type)) . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
