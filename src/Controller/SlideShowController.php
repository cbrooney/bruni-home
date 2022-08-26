<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SlideShowController extends AbstractController
{
    /**
     * @Route("/slideshow", name="slide_show", methods={"GET"})
     */
    public function slides(): Response
    {
        $number = random_int(100, 1000);

//        echo '<html>
//<body>
//<div class="rahmen" id="myPicture"> <img loading=lazy id="hochkantBg" class="imagesHoch" src="20161231_122254.jpg"><img loading=lazy id="hochkant" class="imagesHoch" src="20161231_122254.jpg"></div>
//<img loading=lazy id="hochkant" class="imagesHoch" src="20161231_122254.jpg"></div>
//</body>
//</html>';

//        echo '<div class="rahmen" id="myPicture"> <img loading=lazy id="hochkantBg" class="imagesHoch" src="//home//cbruni//GIT//bruni-home//bruni_home//templates//slideshow//20161231_122254.jpg">'."\n";
//        echo '<img loading=lazy id="hochkant" class="imagesHoch" src="//home//cbruni//GIT//bruni-home//bruni_home//templates//slideshow//20161231_122254.jpg">'."\n".'</div>'."\n";

//        return new Response(
//            '<html>
//<body>
//<div
//class="rahmen" id="myPicture"> <img loading=lazy id="hochkantBg" class="imagesHoch" src="bilder/PXL_20210622_045016400.jpg">
//</div>
//</body>
//</html>'
//        );

        return $this->render('slideshow/slides.html.twig', [
            'number' => $number,
        ]);
    }
}
