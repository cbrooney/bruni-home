<?php

declare(strict_types=1);

namespace App\FileManager\App\Controller;

use App\FileManager\App\Repository\FileListEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SlideShowController extends AbstractController
{
    private $bilderDir;
    private FileListEntityRepository $fileListEntityRepository;

    public function __construct(
        string $bilderDir,
        FileListEntityRepository $fileListEntityRepository
    ) {
        $this->bilderDir = $bilderDir;
        $this->fileListEntityRepository = $fileListEntityRepository;
    }

    /**
     * @Route("/slideshow", name="slide_show", methods={"GET"})
     */
    public function slides(): Response
    {
        $fileTypes = ["JPG","jpg", "jpeg","webm"];
        $fileNameList = [];

        $directoryEntries = scandir($this->bilderDir);
        $directoryEntries = array_diff(
            $directoryEntries,
            ['..', '.', '.gitkeep']
        );

        //var_dump($directoryEntries);
        //die();

        return $this->render('slideshow/slides.html.twig', [
            'directoryEntries' => $directoryEntries,
        ]);
    }

    /**
     * @Route("/slideshow/get-figures", name="slide_show_get_figures", methods={"GET"})
     */
    public function slidesFigure(): Response
    {
        $fileTypes = ["JPG","jpg", "jpeg","webm"];
        $fileNameList = [];

        $randomPictures = $this->getRandomPictures();

        return new JsonResponse(
            ['filenames' => $randomPictures]
        );
    }

    /**
     * @Route("/slideshow-a", name="slide_show_a", methods={"GET"})
     */
    public function slidesFigureA(): Response
    {
        $fileTypes = ["JPG","jpg", "jpeg","webm"];
        $fileNameList = [];

        $directoryEntries = scandir($this->bilderDir);
        $directoryEntries = array_diff(
            $directoryEntries,
            ['..', '.', '.gitkeep']
        );


        return $this->render('slideshow/slides-a.html.twig', [
            'directoryEntries' => $directoryEntries,
        ]);
    }

    /**
     * @Route("/slideshow-ordered", name="slide_show_ordered", methods={"GET"})
     */
    public function slidesOrdered(): Response
    {
        $randomPictures = $this->getRandomPictures();

        return $this->render('slideshow/slides-ordered.html.twig', [
            'directoryEntries' => $randomPictures,
        ]);
    }

    /**
     * @Route("/slideshow-full", name="slide_show_fullscreen", methods={"GET"})
     */
    public function slidesFullscreen(): Response
    {
        $directoryEntries = scandir($this->bilderDir);
        $directoryEntries = array_diff(
            $directoryEntries,
            ['..', '.', '.gitkeep']
        );

        return $this->render('slideshow/slides-full.html.twig', [
            'directoryEntries' => $directoryEntries,
        ]);
    }

    /**
     * @Route("/slideshow-db", name="slide_show_db", methods={"GET"})
     */
    public function slidesFullscreenDb(): Response
    {
        $directoryEntries = $this->fileListEntityRepository->getFiguresToShow();

        // var_dump($directoryEntries);
        // die();

        return $this->render('slideshow/slides.html.twig', [
            'directoryEntries' => $directoryEntries,
        ]);
    }

    /**
     * @Route("/testing", name="testing", methods={"GET"})
     */
    public function testing(): Response
    {
        $directoryEntries = scandir($this->bilderDir);
        $directoryEntries = array_diff(
            $directoryEntries,
            ['..', '.', '.gitkeep']
        );

        return $this->render('slideshow/testing.html.twig', [
            'directoryEntries' => $directoryEntries,
        ]);
    }

    /** @return array<string> */
    private function getRandomPictures(): array
    {
        $directoryEntries = scandir($this->bilderDir);
        $directoryEntries = array_diff(
            $directoryEntries,
            ['..', '.', '.gitkeep']
        );

        shuffle($directoryEntries);
        $randomKeys = array_rand($directoryEntries, 10);

        return array_intersect_key($directoryEntries, $randomKeys);
    }
}
