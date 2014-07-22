<?php

namespace Book\RequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExampleController extends Controller
{

    public function serviceAction($name) {
        return new Response('<html><body>Hello '.$name.'</body></html>');
    }

    public function servicecallAction($name) {
        return $this->forward('book.request.controller:serviceAction', array('name' => $name));
    }

    public function indexAction($example_name = 'request', $example_number = 1)
    {
        $examples = array();
        switch (strtolower($example_name)) {
            case 'request':
                $examples = $this->exampleRequest();
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
            case 'httpfoundation':
                $examples = $this->exampleHttpFoundation();
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
            case 'response':
                $response = $this->exampleResponse();
                return $response;
                break;
            case 'redirect':
                $response = $this->exampleRedirect();
                return $response;
                break;
            case 'streamingresponse':
                $response = $this->exampleStreamingResponse();
                return $response;
                break;
            case 'servingfiles':
                $response = $this->exampleServingFiles();
                return $response;
                break;
            case 'jsonresponse':
                $response = $this->exampleJSONResponse($example_number);
                return $response;
                break;
            default:
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
//              return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
        }
    }

    private function exampleRequest() {
        $request = Request::createFromGlobals();

        $examples = array(
            array('label'=>'HEADER_CLIENT_IP'                                                   , 'value'=>Request::HEADER_CLIENT_IP),
            array('label'=>'HEADER_CLIENT_HOST'                                                 , 'value'=>Request::HEADER_CLIENT_HOST),
            array('label'=>'HEADER_CLIENT_PROTO'                                                , 'value'=>Request::HEADER_CLIENT_PROTO),
            array('label'=>'HEADER_CLIENT_PORT'                                                 , 'value'=>Request::HEADER_CLIENT_PORT),
            array('label'=>'__construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), string $content = null)'  , 'value'=>'-'),
            array('label'=>'initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), string $content = null)'   , 'value'=>'-'),
            array('label'=>'static Request createFromGlobals()'                                 , 'value'=>$request->createFromGlobals()),
            array('label'=>'static Request create(string $uri, string $method = "GET", array $parameters = array(), array $cookies = array(), array $files = array(), array $server = array(), string $content = null)'     , 'value'=>'-'),
            array('label'=>'static setFactory(callable|null $callable)'                         , 'value'=>'-'),
            array('label'=>'Request duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)'                                      , 'value'=>'-'),
            array('label'=>'__clone()'                                                          , 'value'=>'-'),
            array('label'=>'string __toString()'                                                , 'value'=>$request->__toString()),
            array('label'=>'overrideGlobals()'                                                  , 'value'=>$request->overrideGlobals()),
            array('label'=>'static setTrustedProxies(array $proxies)'                           , 'value'=>'-'),
            array('label'=>'static array getTrustedProxies()'                                   , 'value'=>implode(', ',$request->getTrustedProxies())),
            array('label'=>'static setTrustedHosts(array $hostPatterns)'                        , 'value'=>'-'),
            array('label'=>'static array getTrustedHosts()'                                     , 'value'=>'-'),
            array('label'=>'static setTrustedHeaderName(string $key, string $value)'            , 'value'=>'-'),
            array('label'=>'static string getTrustedHeaderName(string $key)'                    , 'value'=>'-'),
            array('label'=>'static public string normalizeQueryString(string $qs)'              , 'value'=>$request->normalizeQueryString('d=uuu&b=ooo&a=ccc')),
            array('label'=>'static enableHttpMethodParameterOverride()'                         , 'value'=>$request->enableHttpMethodParameterOverride()),
            array('label'=>'static public bool getHttpMethodParameterOverride()'                , 'value'=>$request->getHttpMethodParameterOverride()),
            array('label'=>'mixed get(string $key, mixed $default = null, bool $deep = false)'  , 'value'=>$request->get('foo', 'Default')),
            array('label'=>'SessionInterface|null getSession()'                                 , 'value'=>$request->getSession()),
            array('label'=>'bool hasPreviousSession()'                                          , 'value'=>(($request->hasPreviousSession()))?'1':'0'),
            array('label'=>'bool hasSession()'                                                  , 'value'=>(($request->hasSession()))?'1':'0'),
            array('label'=>'setSession(SessionInterface $session)'                              , 'value'=>'-'),
            array('label'=>'array getClientIps()'                                               , 'value'=>implode(', ',$request->getClientIps())),
            array('label'=>'string getClientIp()'                                               , 'value'=>$request->getClientIp()),
            array('label'=>'string getScriptName()'                                             , 'value'=>$request->getScriptName()),
            array('label'=>'string getPathInfo()'                                               , 'value'=>$request->getPathInfo()),
            array('label'=>'string getBasePath()'                                               , 'value'=>$request->getBasePath()),
            array('label'=>'string getBaseUrl()'                                                , 'value'=>$request->getBaseUrl()),
            array('label'=>'string getScheme()'                                                 , 'value'=>$request->getScheme()),
            array('label'=>'string getPort()'                                                   , 'value'=>$request->getPort()),
            array('label'=>'string|null getUser()'                                              , 'value'=>$request->getUser()),
            array('label'=>'string|null getPassword()'                                          , 'value'=>$request->getPassword()),
            array('label'=>'string getUserInfo()'                                               , 'value'=>$request->getUserInfo()),
            array('label'=>'string getHttpHost()'                                               , 'value'=>$request->getHttpHost()),
            array('label'=>'string getRequestUri()'                                             , 'value'=>$request->getRequestUri()),
            array('label'=>'string getSchemeAndHttpHost()'                                      , 'value'=>$request->getSchemeAndHttpHost()),
            array('label'=>'string getUri()'                                                    , 'value'=>$request->getUri()),
            array('label'=>'string getUriForPath(string $path)'                                 , 'value'=>$request->getUriForPath('?index.php&a=b&c=d')),
            array('label'=>'string|null getQueryString()'                                       , 'value'=>$request->getQueryString()),
            array('label'=>'bool isSecure()'                                                    , 'value'=>(($request->isSecure()))?'1':'0'),
            array('label'=>'string getHost()'                                                   , 'value'=>$request->getHost()),
            array('label'=>'setMethod(string $method)'                                          , 'value'=>$request->setMethod('POST')),
            array('label'=>'string getMethod()'                                                 , 'value'=>$request->getMethod()),
            array('label'=>'string getRealMethod()'                                             , 'value'=>$request->getRealMethod()),
            array('label'=>'string getMimeType(string $format)'                                 , 'value'=>'-'),
            array('label'=>'string|null getFormat(string $mimeType)'                            , 'value'=>'-'),
            array('label'=>'setFormat(string $format, string|array $mimeTypes)'                 , 'value'=>'-'),
            array('label'=>'string getRequestFormat(string $default = "html")'                  , 'value'=>$request->getRequestFormat()),
            array('label'=>'setRequestFormat(string $format)'                                   , 'value'=>$request->setRequestFormat('xml')),
            array('label'=>'string|null getContentType()'                                       , 'value'=>$request->getContentType()),
            array('label'=>'setDefaultLocale(string $locale)'                                   , 'value'=>$request->setDefaultLocale('en')),
            array('label'=>'setLocale(string $locale)'                                          , 'value'=>$request->setLocale('sk')),
            array('label'=>'string getLocale()'                                                 , 'value'=>$request->getLocale()),
            array('label'=>'bool isMethod(string $method)'                                      , 'value'=>(($request->isMethod($request)))?'1':'0'),
            array('label'=>'bool isMethodSafe()'                                                , 'value'=>(($request->isMethodSafe()))?'1':'0'),
            array('label'=>'string|resource getContent(bool $asResource = false)'               , 'value'=>$request->getContent()),
            array('label'=>'array getETags()'                                                   , 'value'=>implode(', ',$request->getETags())),
            array('label'=>'bool isNoCache()'                                                   , 'value'=>(($request->isNoCache()))?'1':'0'),
            array('label'=>'string|null getPreferredLanguage(array $locales = null)'            , 'value'=>$request->getPreferredLanguage()),
            array('label'=>'array getLanguages()'                                               , 'value'=>implode(', ',$request->getLanguages())),
            array('label'=>'array getCharsets()'                                                , 'value'=>implode(', ',$request->getCharsets())),
            array('label'=>'array getEncodings()'                                               , 'value'=>implode(', ',$request->getEncodings())),
            array('label'=>'array getAcceptableContentTypes()'                                  , 'value'=>implode(', ',$request->getAcceptableContentTypes())),
            array('label'=>'bool isXmlHttpRequest()'                                            , 'value'=>(($request->isXmlHttpRequest()))?'1':'0'),
        );
        return $examples;
    }

    private function exampleHttpFoundation() {
     //   $request = Request::createFromGlobals();

        $url = '/hello-world?foo=bar&fooa[k]=v&fooa[k2]=v2';
        $request = Request::create($url, 'POST', array('name' => 'Fabien', 'city'=>'Bratislava1'));



 //       $request->overrideGlobals();

        $post           = $request->request->get('name', 'default POST value');
        $get            = $request->query->get('example_number', 'default GET value');

        $cookies        = $request->cookies->all();

        $attributes     = $request->attributes->all();

        // retrieve SERVER variables
        $http_host      = $request->server->get('HTTP_HOST');
        $queryString    = $request->server->get('QUERY_STRING');

        $files          = $request->files->get('foo');

        $host           = $request->headers->get('host');
        $content_type   = $request->headers->get('content_type');

        $examples[] = array('label'=>'CREATE REQUEST'                               , 'value'=>'$request'." = Request::create('".$url."', 'POST', array('name' => 'Fabien', 'city'=>'Bratislava'))");

        $examples[] = array('label'=>'POST'                                         , 'value'=>var_export($request->request->all()   , true));
        $examples[] = array('label'=>'$request->request->all()'                     , 'value'=>var_export($request->request->all()  , true));
        $examples[] = array('label'=>'$request->request->keys()'                    , 'value'=>var_export($request->request->keys()  , true));
        $request->request->replace(array('firstName'=>'Ján', 'lastName'=>'Polák'    , 'testtext'=>'Šmidkeho 17', 'email'=>'jan.polak@qv.sk'));
        $examples[] = array('label'=>'$request->request->replace($parameters)'      , 'value'=>var_export($request->request->all()  , true));
        $request->request->add(array('tittle'=>'Ing'));
        $examples[] = array('label'=>'$request->request->add($parameters)'          , 'value'=>var_export($request->request->all()  , true));
        $firstname = $request->request->get('firstName');
        $examples[] = array('label'=>'$request->request->get("firstName")'          , 'value'=>$firstname);
        $request->request->set('firstName', 'Janko') ;
        $examples[] = array('label'=>'$request->request->set("firstName", "Janko")' , 'value'=>var_export($request->request->all()  , true));
        $examples[] = array('label'=>'$request->request->has("firstName")'          , 'value'=>var_export($request->request->has("firstName"), true));
        $request->request->remove("tittle");
        $examples[] = array('label'=>'$request->request->remove("tittle")'          , 'value'=>var_export($request->request->all()  , true));
        $examples[] = array('label'=>'$request->request->getAlpha("testtext")'      , 'value'=>$request->request->getAlpha('testtext'));
        $examples[] = array('label'=>'$request->request->getAlnum("testtext")'      , 'value'=>$request->request->getAlnum('testtext'));
        $examples[] = array('label'=>'$request->request->getDigits("testtext")'     , 'value'=>$request->request->getDigits('testtext'));
        $examples[] = array('label'=>'$request->request->getInt("testtext")'        , 'value'=>$request->request->getInt('testtext'));

        $examples[] = array('label'=>'GET'                                          , 'value'=>var_export($request->query->all()   , true));
        $examples[] = array('label'=>'$request->query->get("foo")'                  , 'value'=>$request->query->get('foo'));
        $examples[] = array('label'=>'$request->query->get("bar")'                  , 'value'=>$request->query->get('bar'));
        $examples[] = array('label'=>'$request->query->get("bar","default")'        , 'value'=>$request->query->get('bar', 'default'));
        $examples[] = array('label'=>'$request->query->get("fooa")'                 , 'value'=>var_export($request->query->get('fooa'), true));
        $examples[] = array('label'=>'$request->query->get("fooa[k]")'              , 'value'=>var_export($request->query->get('fooa[k]'), true));
        $examples[] = array('label'=>'$request->query->get("fooa[k2]", null, true)' , 'value'=>var_export($request->query->get('fooa[k2]', null, true), true));

        $examples[] = array('label'=>'COOKIES'                                      , 'value'=>var_export($request->cookies->all()  , true));

        $examples[] = array('label'=>'ATTRIBUTES'                                   , 'value'=>var_export($request->attributes->all(), true));
        $request->attributes->add(array('sex'=>'male', 'age'=>55));
        $label = '$request'."->attributes->add(array('sex'=>'male', 'age'=>55))";
        $examples[] = array('label'=>$label                                         , 'value'=>var_export($request->attributes->all(), true));

        $examples[] = array('label'=>'FILES'                                        , 'value'=>var_export($request->files->all()    , true));
        $examples[] = array('label'=>'SERVER'                                       , 'value'=>var_export($request->server->all()   , true));
        $examples[] = array('label'=>'HEADERS'                                      , 'value'=>var_export($request->headers->all()  , true));

        $accept = AcceptHeader::fromString($request->headers->get('Accept'));
        if ($accept->has('text/html')) {
            $item       = $accept->get('text/html');
            $charset    = $item->getAttribute('charset', 'utf-8');
            $value      = $item->getValue();
            $quality    = $item->getQuality();
            $index      = $item->getIndex();
            $attributes = $item->getAttributes();
            $examples[] = array('label'=>'text/html'                                  , 'value'=>var_export($item, true));
        }
        // Accept header items are sorted by descending quality
        $accepts = AcceptHeader::fromString($request->headers->get('Accept'))->all();
        $examples[] = array('label'=>'AcceptHeader'                                  , 'value'=>var_export($accepts, true));

        $examples[] = array('label'=>'string|resource getContent(bool $asResource = false)'               , 'value'=>$request->getContent());
        $examples[] = array('label'=>'string getPathInfo()'                                               , 'value'=>$request->getPathInfo());


        return $examples;
    }

    private function exampleResponse() {
        $response = new Response(
            'Content',
            Response::HTTP_OK,
            array('content-type' => 'text/plain')
        );
        $response->setContent('Hello World žťčšľéíáý');
// the headers public attribute is a ResponseHeaderBag
        $response->headers->set('Content-Type', 'text/html');
        $response->setCharset('UTF-8');
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        $response->headers->setCookie(new Cookie('foor', 'barr'));
        $response->setCache(array(
            'etag'          => 'abcdef',
            'last_modified' => new \DateTime(),
            'max_age'       => 600,
            's_maxage'      => 600,
            'private'       => false,
            'public'        => true,
        ));
        return $response;
    }

    private function exampleRedirect() {
        $response = new RedirectResponse('http://symfony2:8888/app_dev.php/book/request');
        return $response;
    }

    private function exampleStreamingResponse() {
        $response = new StreamedResponse();
        $response->setCallback(function () {
            echo '<p>Hello World 1</p>';
            flush();
            sleep(2);
            echo '<p>Hello World 2</p>';
            flush(); });
//        $response->send();
        return $response;
    }

    private function exampleServingFiles() {
        $response = new Response('Content');
        $d = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'foo.txt'
        );
        $response->headers->set('Content-Disposition', $d);

//        use Symfony\Component\HttpFoundation\BinaryFileResponse;
//        $file = 'path/to/file.txt';
//        $response = new BinaryFileResponse($file);

//        $response->headers->set('Content-Type','text/plain');
//        $response->setContentDisposition(
//            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
//            'filename.txt'
//        );

        return $response;
    }

    private function exampleJSONResponse($example_number) {
        switch ($example_number) {
            case 1:
                $response = new Response();
                $response->setContent(json_encode(array(
                    'data' => 123,
                )));
                $response->headers->set('Content-Type', 'application/json');
                break;
            case 2:
                $response = new JsonResponse();
                $response->setData(array(
                    'data' => 12345
                ));
                break;
            default:
                $response = new Response('Neznáma akcia');
                break;
        }
        return $response;
    }

}
