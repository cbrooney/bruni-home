<?php

declare(strict_types=1);

namespace App\FileManager\App\Controller;

use App\FileManager\App\Repository\FileListEntityRepository;
use App\FileManager\ValueObject\SinglePicutureRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

class SlideShowController extends AbstractController
{
    private $bilderDir;
    private FileListEntityRepository $fileListEntityRepository;
    private SerializerInterface $serializer;

    public function __construct(
        string $bilderDir,
        FileListEntityRepository $fileListEntityRepository,
        SerializerInterface $serializer
    ) {
        $this->bilderDir = $bilderDir;
        $this->fileListEntityRepository = $fileListEntityRepository;
        $this->serializer = $serializer;
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
        return $this->render('slideshow/slides-db-2.html.twig', []);
    }

    /**
     * @Route("/figures-to-show", name="figures_to_show", methods={"GET"})
     */
    public function getFiguresToShow(): Response
    {
        $figuresToShow = $this->fileListEntityRepository->getFiguresToShow();

        $jsonResponseContent = $this->serializer->serialize($figuresToShow, JsonEncoder::FORMAT);

        return new JsonResponse($jsonResponseContent, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/single-picture", name="single_picture", methods={"POST"})
     */
    public function getSinglePicture(Request $request): Response
    {
        try {
            /** @var SinglePicutureRequest $singlePicutureRequest */
            $singlePicutureRequest = $this->serializer->deserialize(
                (string) $request->getContent(),
                SinglePicutureRequest::class,
                'json'
            );

            $fileEntity = $this->fileListEntityRepository->getByFullPath($singlePicutureRequest->getFullPath());
            $img = file_get_contents($fileEntity->getFullPath());
        } catch (Throwable $exception) {
            return new Response($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(
            [
                'filenameFromRequest' => $fileEntity->getFileName(),
                'base64Picture' => base64_encode($img),
                'timestamp' => $fileEntity->getMTime()->format('Y-m-d'),
            ]
        );
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
