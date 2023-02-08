<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use Exception;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface DartMatchesInterface
{
    public function supports(string $type): bool;

    /**
     * @throws Exception
     */
    public function recordNewMatch(
        QuestionHelper $questionHelper,
        InputInterface $input,
        OutputInterface $output
    ): int;
}
