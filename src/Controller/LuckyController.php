<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
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
}
