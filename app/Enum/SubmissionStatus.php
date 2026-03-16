<?php

namespace App\Enum;

enum SubmissionStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case FINALLY_CLOSED = 'finally_closed';

    /**
     * Get all status values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all status cases
     */
    public static function casesArray(): array
    {
        return [
            self::OPEN,
            self::CLOSED,
            self::FINALLY_CLOSED,
        ];
    }

    /**
     * Check if submission allows comments
     */
    public function canComment(): bool
    {
        return $this === self::OPEN || $this === self::CLOSED;
    }
    /**
     * Check if status allows adding new ideas
     */
    public function canAcceptIdeas(): bool
    {
        return $this === self::OPEN;
    }

    /**
     * Check if submission can be modified
     */
    public function canBeModified(): bool
    {
     
        return $this === self::OPEN;
    }

    /**
     * Get description for status
     */
    public function description(): string
    {
        return match ($this) {
            self::OPEN => 'Submission is open for new ideas',
            self::CLOSED => 'Submission is closed for new ideas but still active',
            self::FINALLY_CLOSED => 'Submission is completely closed',
        };
    }
}
