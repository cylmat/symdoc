<?php

namespace App\Application\Controller;

use App\Application\FormCreator\FormCreator;
use App\Application\Response;
use App\Domain\Entity\Product;
use App\Domain\Entity\User;
use App\Domain\Manager\RedisManager;
use App\Domain\Repository\ProductRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * Route groups:
 * @Route("", requirements={"_locale": "en|fr"}, name="")
 */
final class BasicsController extends AbstractController implements LoggerAwareInterface
{
    // Used in FrameworkExtension
    use LoggerAwareTrait;

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
     * make:registration-form
     *
     * Ensure that each test run in a separate env (PHPUnit extension or listener)
     * composer require --dev dama/doctrine-test-bundle
     *
     * https://symfony.com/doc/5.0/reference/configuration/doctrine.html
     * sensio/framework-extra-bundle
     * antishov/StofDoctrineExtensionsBundle
     */
    /**
     * @Route("/doctrine")
     */
    public function doctrine(
        ValidatorInterface $validator,
        ProductRepository $productRepository,
        KernelInterface $kernel
    ): Response {
        // Run command migration //
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->run(new ArrayInput([
            'command' => 'doctrine:migrations:migrate',
            '--no-interaction' => true
        ]), new BufferedOutput());

        // action //
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

    // Loaded from App\Application\Routing\CustomLoader
    public function routing()
    {
        return new Response([
            'data' => []
        ]);
    }

    // Forms /////////////////////////////////////////////////////
    /**
     * @Route("/form")
     */
    public function form(Request $request, FormCreator $formCreator): Response
    {
        $form = $this->createFormBuilder(['email' => 'my@a'])
            ->add('email', EmailType::class, ['constraints' => new Length(['min' => 3]),])
            ->add('send', SubmitType::class)
            ->getForm();

        //test
        $testForm = new class extends TypeTestCase
        {
            public function test()
            {
                $this->factory->create(TestedType::class, new User());
                $form->submit(['data']);
                $form->isSynchronized();
                $view = $this->factory->create(TestedType::class, ['data'])->createView();
                $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
            }
        };

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
     * framework.session
     *
     * @Route("/session")
     */
    public function session(Request $request, SessionInterface $session)
    {
        $session->set('thank_to_namespaced/key', 'my_value');

        return new Response([
            'data' => [
                'session' => $session,
                '_locale' => $request->attributes->get('_locale'),
            ],
        ]);
    }

    /**
     * @Route("/cache")
     *
     * Symfont supports Cache Contracts, PSR-6/16 and Doctrine Cache interfaces
     * cache.global_clearer - all the cache items in every pool
     * cache.system_clearer - used in the bin/console cache:clear
     * cache.app_clearer    - default clearer, all custom pools
     *
     * cache:pool:list
     * cache:pool:clear [pool]
     * cache:pool:clear cache.global_clearer
     *
     * @Cache(public=false, maxage=5)
     */
    public function cache(
        RedisManager $redisManager,
        TagAwareCacheInterface $customThingCache,
        CacheInterface $myFoobarCachePool
    ): Response {
        $fsCache = new FilesystemAdapter();
        /** @var \Symfony\Component\Cache\CacheItem $item */
        $item = $fsCache->getItem('item_0');
        $item->set('beta');
        $fsCache->save($item);

        $value0 = $customThingCache->get('item_0', function (ItemInterface $item) {
            $item->tag(['foo']);
            return 'debug0';
        });

        $value1 = $customThingCache->get('key1', function (ItemInterface $item) {
            $item->tag(['foo', 'bar']);
            return 'debug1';
        });

        // Remove all cache keys tagged with "bar"
        $customThingCache->invalidateTags(['foo', 'bar']);

        return new Response([
            'data' => [
                'redis' => $redisManager->call(),
                'custom' => $customThingCache,
                'pool' => $myFoobarCachePool,
                'fsCache' => $fsCache,
                'keys' => [
                    'value0' => $value0,
                    'value1' => $value1,
                    'item' => $item,
                ]
            ],
        ]);
    }

    /**
     * @Route("/logger")
     *
     * Channels are done on LoggerChannelPass.php
     * LoggerInterface $<channel>Logger
     *   or
     * tag your service with ("monolog.logger" channel="my_own_channel")
     */
    public function logger(LoggerInterface $logger, LoggerInterface $consoleLogger): Response
    {
        $logger->info('Logging');

        // from autocalled 'setLogger'
        $this->logger->info('from autocalled LoggerAwareInterface');

        return new Response([
            'data' => [
                'logger' => $logger,
                '$this->logger' => $this->logger,
                'main logger' => $consoleLogger,
            ],
        ]);
    }
}
