<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

interface MatchSelector
{
    public function startNewMatch(string $type): void;
}
