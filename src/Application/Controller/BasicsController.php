<?php

namespace App\Application\Controller;

use App\Application\FormCreator\FormCreator;
use App\Application\Response;
use App\Domain\Entity\Product;
use App\Domain\Manager\RedisManager;
use App\Domain\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Route groups:
 * @Route("", requirements={"_locale": "en|fr"}, name="")
 */
final class BasicsController extends AbstractController
{
    private $bfc;

    public function __construct(BasicsFormController $bfc)
    {
        $this->bfc = $bfc;
    }

    /*
     * There is a limit of 767 bytes for the index key prefix
     *    when using InnoDB tables in MySQL 5.6 and earlier versions.
     * String columns with 255 character length and utf8mb4 encoding surpass that limit.
     * This means that any column of type string and unique=true must set its maximum length to 190.
     * Otherwise, you'll see this error: "[PDOException] SQLSTATE[42000]:
     * Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes".
     */
    /*
     * doctrine:schema:create
     * dbal:run-sql 'SELECT * FROM product'
     * make:entity --regenerate
     *
     * https://symfony.com/doc/5.0/reference/configuration/doctrine.html
     * sensio/framework-extra-bundle
     * antishov/StofDoctrineExtensionsBundle
     */
    /**
     * @Route("/doctrine")
     */
    public function doctrine(ValidatorInterface $validator, ProductRepository $productRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $entityManager->persist($product);
        $entityManager->flush();

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response([(string) $errors], 400);
        }

        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find(1);

        $product = $productRepository->findOneBy([
            'id' => 1,
            //'name' => 'Keyboard',
            //'price' => 1999,
        ]);

        if (!$product) {
            /*throw*/ $this->createNotFoundException(
                'No product found for id 1'
            );
        }

        return new Response([
            'data' => $product
        ]);
    }

    // Forms /////////////////////////////////////////////////////
    /**
     * @Route("/form")
     */
    public function form(Request $request, FormCreator $formCreator): Response
    {
        return $this->bfc->form($request, $formCreator);
    }

    /**
     * @Route("/form-build")
     */
    public function formBuild(Request $request, FormCreator $formCreator): Response
    {
        return $this->bfc->formBuild($request, $formCreator);
    }
    // -forms

    /**
     * @Route("/cache")
     */
    public function cache(RedisManager $redisManager): Response
    {
        return new Response([
            'data' => $redisManager->call(),
        ]);
    }
}
