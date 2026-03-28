<?php

namespace App\Services;

use App\Enum\AnonymousEnum;
use App\Repositories\React\ReactRepositoryInterface;

class ReactService
{
    public function __construct(
        protected ReactRepositoryInterface $reactRepository,
    ) {
        //
    }

    public function handleToggle($userId, $ideaId, $newValue, $isAnonymous)
    {
        $current = $this->reactRepository->findUserReact($userId, $ideaId);

        $previous = null;
        if ($current) {
            $previous = $current->react instanceof \App\Enum\ReactEnum
                ? $current->react->value
                : $current->react;
        }

        if ($current && ($current->react instanceof \App\Enum\ReactEnum
            ? $current->react->value === $newValue
            : $current->react === $newValue)) {
            $this->reactRepository->delete($current);
            return [
                'status' => 'none',
                'react_user' => $previous,
                'message' => 'React removed'
            ];
        }

        $this->reactRepository->updateOrCreate($userId, $ideaId, $newValue, $isAnonymous);

        return [
            'status' => $newValue,
            'react_user' => $previous,
            'is_anonymous' => $isAnonymous,
            'message' => __('Successfully reacted')
        ];
    }
}
