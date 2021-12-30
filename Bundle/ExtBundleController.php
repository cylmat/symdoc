<?php

namespace Bundle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExtBundleController extends AbstractController
{
    private const INDEX = __DIR__ . "/Phpext/public/index.php";

    public static function isAccessible(): bool
    {
        return file_exists(self::INDEX);
    }

    public function __invoke()
    {
        if (!self::isAccessible()) {
            return $this->createNotFoundException();
        }

        ob_start();
        include self::INDEX;
        $render = ob_get_clean();
        $render = preg_replace('/<\/?html>|<\/?body>/', '', $render);

        return $this->render('extbundle.html.twig', ['render' => $render]);
    }
}
