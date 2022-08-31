<?php

namespace App\Application\Controller;

use App\Application\Response;
use App\Domain\Manager\Utilities\ComponentManager;
use App\Domain\Manager\Utilities\ExpressionManager;
use App\Domain\Manager\FormatManager;
use App\Domain\Manager\PhpManager;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpClient\Response\StreamWrapper;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class UtilitiesController extends AbstractController
{
    private const DATETIME_PARIS = 'Europe/Paris';

    private $componentController;

    public function __construct(UtilitiesComponentController $componentController)
    {
        $this->componentController = $componentController;
    }

    /**
     * @Route("/http-client")
     *
     * Can use Symfony Contracts, PSR-18, HTTPlug v1/v2 and native PHP streams
     */
    public function httpclient(Request $request, HttpClientInterface $httpClient): Response
    {
        $host = $request->server->get('SYMFONY_PROJECT_DEFAULT_ROUTE_URL');
        $url = $host . $this->generateUrl('app_application_utilities_expression');

        //$response = HttpClient::create()->request(Request::METHOD_GET, $url);
        //  both the native PHP streams and cURL to make the HTTP requests
        $response = $httpClient->request(Request::METHOD_GET, $url, [
            'max_redirects' => 0,
            'query' => [
                'token' => '...',
                'name' => '...',
            ],
            'auth_basic' => ['the-username', 'the-password'],
            'headers' => [
                'FROM-HTTP-CLIENT' => true,
            ],
            'body' => function (int $size): string {
                return '';
            },
            'no_proxy' => true,
            //'body' => fopen('/path/to/file', 'r'),
            // or
            //'json' => ['param1' => 'value1', '...'],

            // no cookies because it's stateless
            'on_progress' => function (int $dlNow, int $dlSize, array $info): void {
                // $dlNow is the number of bytes downloaded so far
                // $dlSize is the total size to be downloaded or -1 if it is unknown
                // $info is what $response->getInfo() would return at this very time
            },
            'timeout' => 2.5 // TransportExceptionInterface throw
        ]);

        // specific
        $client = new NativeHttpClient();
        $client = new CurlHttpClient();
        $client = new ScopingHttpClient($client, []);

        $formFields = [
            'regular_field' => 'some value',
            'file_field' => DataPart::fromPath(__FILE__),
        ];
        $formData = new FormDataPart($formFields);
        // $response->cancel();

        // stream
        $url = 'https://releases.ubuntu.com/18.04.1/ubuntu-18.04.1-desktop-amd64.iso';
        //$fileHandler = fopen('/tmp/ubuntu.iso', 'w');
        /*foreach ($client->stream($response, 1.5) as $response => $chunk) {
            fwrite($fileHandler, $chunk->getContent());

            // if you want to cancel throw new \MyException();
        }*/

        /** https://httpbin.org/status/404 */
        try {
            #$response = $client->request('GET', 'https://httpbin.org/status/403');
            $headers = $response->getHeaders(); // response is leazy and async
        } catch (HttpExceptionInterface | TransportExceptionInterface | DecodingExceptionInterface $exception) {
        }

        // async
        #$responses[] = $client->request('GET', 'https://httpbin.org/status/200');
        #$responses[] = $client->request('GET', 'https://httpbin.org/status/200');

        /**
         * Cache
         */
        $store = new Store('/tmp/path.to.cache.storage');
        $client = new CachingHttpClient($client, $store);

        /**
         * Native
         */
        /*$client = HttpClient::create();
        $response = $client->request('GET', 'https://symfony.com/versions.json');

        $streamResource = StreamWrapper::createResource($response, $client);

        // alternatively and contrary to the previous one, this returns
        // a resource that is seekable and potentially stream_select()-able
        $streamResource = $response->toStream();

        echo stream_get_contents($streamResource); // outputs the content of the response

        // later on if you need to, you can access the response from the stream
        $response = stream_get_meta_data($streamResource)['wrapper_data']->getResponse();*/

        /**
         * TESTS
         */
        $responses = [
            new MockResponse('body', ['infos']),
            new MockResponse((function () {
                yield 'hello';
                // empty strings are turned into timeouts so that they are easy to test
                yield '';
                yield 'world';
            })(), ['info']),
        ];

        $client = new MockHttpClient($responses);

        return new Response([
            'data' => [
                'response_headers' => $response->getHeaders(),
                'response' => $response,
            ],
            'current_date' => $this->getDateTime(),
            'start time' => $response->getInfo('start_time'),

            // returns detailed logs about the requests and responses of the HTTP transaction
            'logs' => $response->getInfo('debug'),
        ]);
    }

    private function getDateTime(): string
    {
        return (new DateTime('now', new DateTimeZone(self::DATETIME_PARIS)))->format(DateTime::COOKIE);
    }

    /**
     * @Route("/expression", name="app_application_utilities_expression")
     */
    public function expression(ExpressionManager $expressionManager): Response
    {
        return new Response([
            'data' => $expressionManager->call(),
        ]);
    }

    /**
     * @Route("/format")
     */
    public function format(FormatManager $formatManager): Response
    {
        return new Response([
            'data' => $formatManager->call(),
        ]);
    }

    /**
     * @Route("/php")
     */
    public function phpNoMenu(PhpManager $phpManager): Response
    {
        return new Response([
            'data' => $phpManager->call()
        ]);
    }

    /******************************
     * Components
     ******************************/

    /**
     * @Route("/components/{name}")
     */
    public function components(ComponentManager $miscManager, ?string $name = null): Response
    {
        $this->getUser();
        $ctx = $name ? ['name' => $name] : [];

        return $this->componentController->outsidecalled($miscManager, $ctx);
    }
}
