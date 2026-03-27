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

        if ($current && $current->react === $newValue) {
            $this->reactRepository->delete($current);
            return [
                'status' => 'none',
                'message' => 'React removed'
            ];
        }

        $this->reactRepository->updateOrCreate($userId, $ideaId, $newValue, $isAnonymous);
        
        return [
            'status' => $newValue,
            'is_anonymous' => $isAnonymous,
            'message' => __('Successfully reacted')
        ];
    }
}
