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
        $currentValue = null;


        if ($current) {
            $previous = $current->react instanceof \App\Enum\ReactEnum
                ? $current->react->value
                : $current->react;
        }

        if ($current) {
            $currentValue = $current->react instanceof \App\Enum\ReactEnum
                ? $current->react->value
                : $current->react;
        }

        // FIX CHUẨN
        if ($current && ($newValue === null || $currentValue === $newValue)) {
            $this->reactRepository->delete($current);

            return [
                'status' => null,
                'react_user' => null,
                'message' => 'React removed'
            ];
        }

        $this->reactRepository->updateOrCreate($userId, $ideaId, $newValue, $isAnonymous);

        return [
            'status' => $newValue,
            'react_user' => $newValue,
            'is_anonymous' => $isAnonymous,
            'message' => __('Successfully reacted')
        ];
    }
}
