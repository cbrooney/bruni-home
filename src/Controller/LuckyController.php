<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestEntity;
use App\Repository\TestEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    private TestEntityRepository $testEntityRepository;

    public function __construct(TestEntityRepository $testEntityRepository)
    {
        $this->testEntityRepository = $testEntityRepository;
    }

    /**
     * @Route("/lucky/number", name="lucky_number", methods={"GET"})
     */
    public function number(): Response
    {
        $number = random_int(0, 100);

        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );

        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }

    /**
     * @Route("/database", name="database_entry", methods={"GET"})
     */
    public function databaseEntry(): Response
    {
        $testEntity = new TestEntity();

        $this->testEntityRepository->add($testEntity, true);

        var_dump($testEntity);

        return $this->render('lucky/database-entry.html.twig', []);
    }

    /**
     * @Route("/php-info", name="php_info", methods={"GET"})
     */
    public function phpInfoAction(): Response
    {
        echo phpInfo();
        die();
    }
}
