<?php

declare(strict_types=1);

namespace App\Darts\App\Service;

use Exception;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface MatchSelector
{
    /**
     * @throws Exception
     */
    public function startNewMatch(
        QuestionHelper $helper,
        InputInterface $input,
        OutputInterface $output,
        string $type
    ): void;
}
