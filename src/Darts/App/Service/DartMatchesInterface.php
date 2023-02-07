<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

interface DartMatchesInterface
{
    public function supports(string $type): bool;
}
