<?php

namespace App\Enum;

enum IdeaStatus: string
{
    case PENDING       = 'pending';
    case APPROVED      = 'approved';
    case REJECTED      = 'rejected';
    case ANONYMOUS     = 'anonymous';
    case NOT_ANONYMOUS = 'not_anonymous';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function casesArray(): array
    {
        return self::cases();
    }

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isAnonymous(): bool
    {
        return $this === self::ANONYMOUS;
    }

    public function description(): string
    {
        return match ($this) {
            self::PENDING       => 'Idea is waiting for approval',
            self::APPROVED      => 'Idea has been approved',
            self::REJECTED      => 'Idea has been rejected',
            self::ANONYMOUS     => 'Idea submitted anonymously',
            self::NOT_ANONYMOUS => 'Idea submitted with user identity',
        };
    }

    public static function getBadge(string $value): string
    {
        return match ($value) {
            self::PENDING->value       => 'badge-warning',
            self::APPROVED->value      => 'badge-success',
            self::REJECTED->value      => 'badge-danger',
            self::ANONYMOUS->value     => 'badge-info',
            self::NOT_ANONYMOUS->value => 'badge-primary',
            default                    => 'badge-secondary',
        };
    }
}
