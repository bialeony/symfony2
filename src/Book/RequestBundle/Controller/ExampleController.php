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
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExampleController extends Controller
{

    public function serviceAction($name) {
        return new Response('<html><body>Hello '.$name.'</body></html>');
    }

    public function servicecallAction($name) {
        return $this->forward('book.request.controller:serviceAction', array('name' => $name));
    }

    public function twigAction($example) {
        switch ($example) {
            case '1':
                $users = array(
                    array('username'=>'Ján Polák', 'active'=>true),
                    array('username'=>'Jano Polák', 'active'=>false),
                    array('username'=>'Daniela Poláková', 'active'=>true),

                );
                $items = array(
                    array('caption'=>'Apple', 'href'=>'http://www.apple.com'),
                    array('caption'=>'Facebook', 'href'=>'http://www.facebook.com'),
                    array('caption'=>'SLSP', 'href'=>'http://www.slsp.sk'),
                );
                $response = $this->render('BookRequestBundle:Example:twig.html.twig', array('page_title'=>'Welcome to Twig!','navigation' => $items, 'users'=>$users));
                break;
            case '2':
                $blog_entries = array(
                    array('title'=>'Poznávanie ...', 'body'=>'Poznávanie vyšších svetov'),
                    array('title'=>'Záhady ...', 'body'=>'Záhady filosofie'),
                    array('title'=>'Paliatívna ...', 'body'=>'Paliatívna starostlivosť ...'),
                );
                $response = $this->render('BookRequestBundle:Example:twig2.html.twig', array('blog_entries'=>$blog_entries));
                break;
            case '3':
                $articles = array(
                    array('title'=>'Poznávanie ...', 'body'=>'Poznávanie vyšších svetov', 'authorName'=>'Rudolf Steiner'),
                    array('title'=>'Záhady ...', 'body'=>'Záhady filosofie', 'authorName'=>'Rudolf Steiner'),
                    array('title'=>'Paliatívna ...', 'body'=>'Paliatívna starostlivosť ...', 'authorName'=>'Monique Tavernierová'),
                );
                $response = $this->render('BookRequestBundle:Example/Article:list.html.twig', array('articles'=>$articles));
                break;
            case '4':
                $response = $this->recentArticlesAction();
                break;
            case '5':
                $articles = array(
                    array('title'=>'Poznávanie ...', 'body'=>'Poznávanie vyšších svetov', 'authorName'=>'Rudolf Steiner'),
                    array('title'=>'Záhady ...', 'body'=>'Záhady filosofie', 'authorName'=>'Rudolf Steiner'),
                    array('title'=>'Paliatívna ...', 'body'=>'Paliatívna starostlivosť ...', 'authorName'=>'Monique Tavernierová'),
                );
                $response = $this->render('BookRequestBundle:Example/Article:list2.html.twig', array('articles'=>$articles));
                break;
            default:
                break;
        }
        return $response;
    }

    public function showArticleAction($slug = 1)
    {
        $articles = array(
            array('title'=>'Poznávanie ...', 'body'=>'Poznávanie vyšších svetov', 'authorName'=>'Rudolf Steiner', 'slug'=>'poznavanie.pdf'),
            array('title'=>'Záhady ...', 'body'=>'Záhady filosofie', 'authorName'=>'Rudolf Steiner', 'slug'=>'zahady.pdf'),
            array('title'=>'Paliatívna ...', 'body'=>'Paliatívna starostlivosť ...', 'authorName'=>'Monique Tavernierová', 'slug'=>'paliativna.pdf'),
        );
        $article = array('title'=>'Poznávanie ...', 'body'=>'Poznávanie vyšších svetov', 'authorName'=>'Rudolf Steiner', 'slug'=>'poznavanie.pdf');
        return $this->render('BookRequestBundle:Example/Article:articleDetails.html.twig', array('article' => $articles[$slug]));
    }

    public function recentArticlesAction($max = 3)
    {
        $articles = array(
            array('title'=>'Poznávanie ...', 'body'=>'Poznávanie vyšších svetov', 'authorName'=>'Rudolf Steiner', 'slug'=>'poznavanie.pdf'),
            array('title'=>'Záhady ...', 'body'=>'Záhady filosofie', 'authorName'=>'Rudolf Steiner', 'slug'=>'zahady.pdf'),
            array('title'=>'Paliatívna ...', 'body'=>'Paliatívna starostlivosť ...', 'authorName'=>'Monique Tavernierová', 'slug'=>'paliativna.pdf'),
        );
        return $this->render('BookRequestBundle:Example/Article:recentList.html.twig', array('articles' => $articles));
    }

    public function indexAction($example_name = 'request', $example = '1', $_route)
    {
        $examples = array();
        switch (strtolower($example_name)) {
            case 'request':
                $examples = $this->exampleRequest($_route);
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
            case 'requestcontext':
                $examples = $this->exampleRequestContext();
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
            case 'httpfoundation':
                $examples = $this->exampleHttpFoundation();
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
            case 'response':
                $response = $this->exampleResponse($example);
                return $response;
                break;
            case 'redirect':
                $response = $this->exampleRedirect($example);
                return $response;
                break;
            case 'forward':
                $response = $this->exampleForward($example);
                return $response;
                break;
            case 'render':
                $response = $this->exampleRender($example);
                return $response;
                break;
            case 'exception':
                $response = $this->exampleException($example);
                return $response;
                break;
            case 'session':
                $response = $this->exampleSession($example);
                return $response;
                break;
            case 'flashbag':
                $response = $this->exampleFlashBag();
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
                $response = $this->exampleJSONResponse($example);
                return $response;
                break;
            case 'expressionlanguage':
                $examples = $this->exampleExpressionLanguage($example);
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
            default:
                return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
//              return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
                break;
        }
    }

    public function routingAction($culture, $example) {
        $examples = array();
        $examples[] = array('label'=>'culture: ', 'value'=>$culture);
        $examples[] = array('label'=>'example: ', 'value'=>$example);

        $params = $this->get('router')->match('/routingarticle/en/2001/yes.html');
        $examples[] = array('label'=>'router.match', 'value'=>var_export($params, true));

        $uri = $this->get('router')->generate('book_request_ex_testrouting', array('example' => 8));
        $examples[] = array('label'=>'generate.uri', 'value'=>$uri);
        $uri = $this->get('router')->generate('book_request_ex_testrouting', array('example' => 8, 'category' => 'Symfony'));
        $examples[] = array('label'=>'generate.uri.relative', 'value'=>$uri);
        $uri = $this->get('router')->generate('book_request_ex_testrouting', array('example' => 8, 'category' => 'Symfony'), true);
        $examples[] = array('label'=>'generate.uri.absolute', 'value'=>$uri);

        return $this->render('BookRequestBundle:Example:routing.html.twig', array('examples' => $examples));
    }

    public function testroutingAction($example) {
        $examples = array();
        $examples[] = array('label'=>'example: ', 'value'=>$example);

        return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
    }

    public function routingarticleAction($culture, $year, $title, $_format) {
        $examples = array();
        $examples[] = array('label'=>'culture: ', 'value'=>$culture);
        $examples[] = array('label'=>'year: '   , 'value'=>$year);
        $examples[] = array('label'=>'title: '  , 'value'=>$title);
        $examples[] = array('label'=>'_format: ', 'value'=>$_format);

        return $this->render('BookRequestBundle:Example:index.html.twig', array('examples' => $examples));
    }

    private function exampleRequest($_route) {
        $request = Request::createFromGlobals();


        $examples[] = array('label'=>'HEADER_CLIENT_IP'                                                   , 'value'=>Request::HEADER_CLIENT_IP);
        $examples[] = array('label'=>'HEADER_CLIENT_HOST'                                                 , 'value'=>Request::HEADER_CLIENT_HOST);
        $examples[] = array('label'=>'HEADER_CLIENT_PROTO'                                                , 'value'=>Request::HEADER_CLIENT_PROTO);
        $examples[] = array('label'=>'HEADER_CLIENT_PORT'                                                 , 'value'=>Request::HEADER_CLIENT_PORT);
        $examples[] = array('label'=>'__construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), string $content = null)'  , 'value'=>'-');
        $examples[] = array('label'=>'initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), string $content = null)'   , 'value'=>'-');
        $examples[] = array('label'=>'static Request createFromGlobals()'                                 , 'value'=>$request->createFromGlobals());
        $examples[] = array('label'=>'static Request create(string $uri, string $method = "GET", array $parameters = array(), array $cookies = array(), array $files = array(), array $server = array(), string $content = null)'     , 'value'=>'-');
        $examples[] = array('label'=>'static setFactory(callable|null $callable)'                         , 'value'=>'-');
        $examples[] = array('label'=>'Request duplicate(array $query = null, array $request = null, array $attributes = null, array $cookies = null, array $files = null, array $server = null)'                                      , 'value'=>'-');
        $examples[] = array('label'=>'__clone()'                                                          , 'value'=>'-');
        $examples[] = array('label'=>'string __toString()'                                                , 'value'=>$request->__toString());
        $request->overrideGlobals();
        $examples[] = array('label'=>'overrideGlobals()'                                                  , 'value'=>'-');
        $examples[] = array('label'=>'static setTrustedProxies(array $proxies)'                           , 'value'=>'-');
        $examples[] = array('label'=>'static array getTrustedProxies()'                                   , 'value'=>implode(', ',$request->getTrustedProxies()));
        $examples[] = array('label'=>'static setTrustedHosts(array $hostPatterns)'                        , 'value'=>'-');
        $examples[] = array('label'=>'static array getTrustedHosts()'                                     , 'value'=>'-');
        $examples[] = array('label'=>'static setTrustedHeaderName(string $key, string $value)'            , 'value'=>'-');
        $examples[] = array('label'=>'static string getTrustedHeaderName(string $key)'                    , 'value'=>'-');
        $examples[] = array('label'=>'static public string normalizeQueryString(string $qs)'              , 'value'=>$request->normalizeQueryString('d=uuu&b=ooo&a=ccc'));
        $request->enableHttpMethodParameterOverride();
        $examples[] = array('label'=>'static enableHttpMethodParameterOverride()'                         , 'value'=>'-');
        $examples[] = array('label'=>'static public bool getHttpMethodParameterOverride()'                , 'value'=>$request->getHttpMethodParameterOverride());
        $examples[] = array('label'=>'mixed get(string $key, mixed $default = null, bool $deep = false)'  , 'value'=>$request->get('foo', 'Default'));
        $examples[] = array('label'=>'SessionInterface|null getSession()'                                 , 'value'=>$request->getSession());
        $examples[] = array('label'=>'bool hasPreviousSession()'                                          , 'value'=>(($request->hasPreviousSession()))?'1':'0');
        $examples[] = array('label'=>'bool hasSession()'                                                  , 'value'=>(($request->hasSession()))?'1':'0');
        $examples[] = array('label'=>'setSession(SessionInterface $session)'                              , 'value'=>'-');
        $examples[] = array('label'=>'array getClientIps()'                                               , 'value'=>implode(', ',$request->getClientIps()));
        $examples[] = array('label'=>'string getClientIp()'                                               , 'value'=>$request->getClientIp());
        $examples[] = array('label'=>'string getScriptName()'                                             , 'value'=>$request->getScriptName());
        $examples[] = array('label'=>'string getPathInfo()'                                               , 'value'=>$request->getPathInfo());
        $examples[] = array('label'=>'string getBasePath()'                                               , 'value'=>$request->getBasePath());
        $examples[] = array('label'=>'string getBaseUrl()'                                                , 'value'=>$request->getBaseUrl());
        $examples[] = array('label'=>'string getScheme()'                                                 , 'value'=>$request->getScheme());
        $examples[] = array('label'=>'string getPort()'                                                   , 'value'=>$request->getPort());
        $examples[] = array('label'=>'string|null getUser()'                                              , 'value'=>$request->getUser());
        $examples[] = array('label'=>'string|null getPassword()'                                          , 'value'=>$request->getPassword());
        $examples[] = array('label'=>'string getUserInfo()'                                               , 'value'=>$request->getUserInfo());
        $examples[] = array('label'=>'string getHttpHost()'                                               , 'value'=>$request->getHttpHost());
        $examples[] = array('label'=>'string getRequestUri()'                                             , 'value'=>$request->getRequestUri());
        $examples[] = array('label'=>'string getSchemeAndHttpHost()'                                      , 'value'=>$request->getSchemeAndHttpHost());
        $examples[] = array('label'=>'string getUri()'                                                    , 'value'=>$request->getUri());
        $examples[] = array('label'=>'string getUriForPath(string $path)'                                 , 'value'=>$request->getUriForPath('?index.php&a=b&c=d'));
        $examples[] = array('label'=>'string|null getQueryString()'                                       , 'value'=>$request->getQueryString());
        $examples[] = array('label'=>'bool isSecure()'                                                    , 'value'=>(($request->isSecure()))?'1':'0');
        $examples[] = array('label'=>'string getHost()'                                                   , 'value'=>$request->getHost());
        $request->setMethod('POST');
        $examples[] = array('label'=>'setMethod(string $method)'                                          , 'value'=>'-');
        $examples[] = array('label'=>'string getMethod()'                                                 , 'value'=>$request->getMethod());
        $examples[] = array('label'=>'string getRealMethod()'                                             , 'value'=>$request->getRealMethod());
        $examples[] = array('label'=>'string getMimeType(string $format)'                                 , 'value'=>'-');
        $examples[] = array('label'=>'string|null getFormat(string $mimeType)'                            , 'value'=>'-');
        $examples[] = array('label'=>'setFormat(string $format, string|array $mimeTypes)'                 , 'value'=>'-');
        $examples[] = array('label'=>'string getRequestFormat(string $default = "html")'                  , 'value'=>$request->getRequestFormat());
        $request->setRequestFormat('xml');
        $examples[] = array('label'=>'setRequestFormat(string $format)'                                   , 'value'=>'-');
        $examples[] = array('label'=>'string|null getContentType()'                                       , 'value'=>$request->getContentType());
        $request->setDefaultLocale('en');
        $examples[] = array('label'=>'setDefaultLocale(string $locale)'                                   , 'value'=>'-');
        $request->setLocale('sk');
        $examples[] = array('label'=>'setLocale(string $locale)'                                          , 'value'=>'-');
        $examples[] = array('label'=>'string getLocale()'                                                 , 'value'=>$request->getLocale());
        $examples[] = array('label'=>'bool isMethod(string $method)'                                      , 'value'=>(($request->isMethod($request)))?'1':'0');
        $examples[] = array('label'=>'bool isMethodSafe()'                                                , 'value'=>(($request->isMethodSafe()))?'1':'0');
        $examples[] = array('label'=>'string|resource getContent(bool $asResource = false)'               , 'value'=>$request->getContent());
        $examples[] = array('label'=>'array getETags()'                                                   , 'value'=>implode(', ',$request->getETags()));
        $examples[] = array('label'=>'bool isNoCache()'                                                   , 'value'=>(($request->isNoCache()))?'1':'0');
        $examples[] = array('label'=>'string|null getPreferredLanguage(array $locales = null)'            , 'value'=>$request->getPreferredLanguage());
        $examples[] = array('label'=>'array getLanguages()'                                               , 'value'=>implode(', ',$request->getLanguages()));
        $examples[] = array('label'=>'array getCharsets()'                                                , 'value'=>implode(', ',$request->getCharsets()));
        $examples[] = array('label'=>'array getEncodings()'                                               , 'value'=>implode(', ',$request->getEncodings()));
        $examples[] = array('label'=>'array getAcceptableContentTypes()'                                  , 'value'=>implode(', ',$request->getAcceptableContentTypes()));
        $examples[] = array('label'=>'bool isXmlHttpRequest()'                                            , 'value'=>(($request->isXmlHttpRequest()))?'1':'0');
        $examples[] = array('label'=>'getParameter'                                                       , 'value'=>$this->container->getParameter('book_request.my_type'));
        $examples[] = array('label'=>'_route'                                                             , 'value'=>$_route);
        $examples[] = array('label'=>'request.headers.get("User-Agent")'                                  , 'value'=>$request->headers->get("User-Agent"));

        return $examples;
    }

    function exampleRequestContext() {
        $examples = array();

        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());

        $examples[] = array('label'=>'string getBaseUrl()'                          , 'value'=>$context->getBaseUrl());
        $examples[] = array('label'=>'setBaseUrl(string $baseUrl)'                  , 'value'=>'-');
        $examples[] = array('label'=>'string getPathInfo()'                         , 'value'=>$context->getPathInfo());
        $examples[] = array('label'=>'setPathInfo(string $pathInfo)'                , 'value'=>'-');
        $examples[] = array('label'=>'string getMethod()'                           , 'value'=>$context->getMethod());
        $examples[] = array('label'=>'setMethod(string $method)'                    , 'value'=>'-');
        $examples[] = array('label'=>'string getHost()'                             , 'value'=>$context->getHost());
        $examples[] = array('label'=>'setHost(string $host)'                        , 'value'=>'-');
        $examples[] = array('label'=>'string getScheme()'                           , 'value'=>$context->getScheme());
        $examples[] = array('label'=>'setScheme(string $scheme)'                    , 'value'=>'-');
        $examples[] = array('label'=>'string getHttpPort()'                         , 'value'=>$context->getHttpPort());
        $examples[] = array('label'=>'setHttpPort(string $httpPort)'                , 'value'=>'-');
        $examples[] = array('label'=>'string getHttpsPort()'                        , 'value'=>$context->getHttpsPort());
        $examples[] = array('label'=>'setHttpsPort(string $httpsPort)'              , 'value'=>'-');
        $examples[] = array('label'=>'string getQueryString()'                      , 'value'=>$context->getQueryString());
        $examples[] = array('label'=>'setQueryString(string $queryString)'          , 'value'=>'-');
        $examples[] = array('label'=>'array getParameters()'                        , 'value'=>var_export($context->getParameters(),true));
        $examples[] = array('label'=>'Route setParameters(array $parameters)'       , 'value'=>'-');
        $examples[] = array('label'=>'mixed getParameter(string $name)'             , 'value'=>'-');
        $examples[] = array('label'=>'setParameter(string $name, mixed $parameter)' , 'value'=>'-');
        $examples[] = array('label'=>'bool hasParameter(string $name)'              , 'value'=>(($context->hasParameter('test')))?'1':'0');

        return $examples;
    }

    private function exampleHttpFoundation() {
     //   $request = Request::createFromGlobals();

        $url = '/hello-world?foo=bar&fooa[k]=v&fooa[k2]=v2';
        $request = Request::create($url, 'POST', array('name' => 'Fabien', 'city'=>'Bratislava1'));



//      $request->overrideGlobals();

//      $post           = $request->request->get('name', 'default POST value');
//      $get            = $request->query->get('example_number', 'default GET value');

//      $cookies        = $request->cookies->all();

//      $attributes     = $request->attributes->all();

        // retrieve SERVER variables
//      $http_host      = $request->server->get('HTTP_HOST');
//      $queryString    = $request->server->get('QUERY_STRING');

//      $files          = $request->files->get('foo');

//      $host           = $request->headers->get('host');
//      $content_type   = $request->headers->get('content_type');

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

    // http://symfony2:8888/app_dev.php/book/response/1
    private function exampleResponse($example) {
        switch ($example) {
            case 1:
                $response = new Response(json_encode(array('name' => $example, 'cities'=>array('Bratislava', 'Piešťany'))));
                $response->headers->set('Content-Type', 'application/json');
                break;
            default:
                $response = new Response(
                    'Content',
                    Response::HTTP_OK,
                    array('Content-Type' => 'text/plain')
                );
                $response->setContent('Hello World žťčšľéíáý');

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
                break;
        }
        return $response;
    }

    // http://symfony2:8888/app_dev.php/book/redirect/book_request_ex_static
    private function exampleRedirect($example, $statusCode = 302) {
        switch ($example) {
            case '1':
                $response = new RedirectResponse('http://symfony2:8888/app_dev.php/book/request');
                break;
            default:
                $response = $this->redirect($this->generateUrl($example));
                break;
        }
        return $response;
    }

    // http://symfony2:8888/app_dev.php/book/forward/Johny
    private function exampleForward($example) {
        switch ($example) {
            case 'Johny':
                $response = $this->forward('AcmeDemoBundle:Demo:hello', array('name'  => $example));
                break;
            default:
                $path = array(
                    '_controller' => 'AcmeDemoBundle:Demo:hello',
                    'name' => $example,
                );
                $request = $this->container->get('request');
                $subRequest = $request->duplicate(array(), null, $path);

                $httpKernel = $this->container->get('http_kernel');
                $response = $httpKernel->handle(
                    $subRequest,
                    HttpKernelInterface::SUB_REQUEST
                );
                break;
        }
        return $response;
    }

    // http://symfony2:8888/app_dev.php/book/render/1
    private function exampleRender($example) {

        switch ($example) {
            case '1':
                $content = $this->renderView('BookRequestBundle:Default:index.html.twig', array('name' => $example));
                $response = new Response($content);
                break;
            case '2':
                $response = $this->render('BookRequestBundle:Default:index.html.twig',array('name' => $example));
                break;
            case '3':
                $templating = $this->get('templating');
                $content = $templating->render('BookRequestBundle:Default:index.html.twig', array('name' => $example));
                $response = new Response($content);
                break;
            case '4':
                $templating = $this->get('templating');
                $content = $templating->render('BookRequestBundle:Default/subdir:index.html.twig', array('name' => $example));
                $response = new Response($content);
                break;
            default:
                $response = new Response('');
                break;
        }
        return $response;
    }

    // http://symfony2:8888/app_dev.php/book/exception/1
    private function exampleException($example) {
        switch ($example) {
            case '1':
                throw $this->createNotFoundException('The product does not exist');
                break;
            case '2':
                throw new \Exception('Something went wrong!');
                break;
        }
        return '';
    }

    // http://symfony2:8888/app_dev.php/book/session/1
    private function exampleSession($example) {
        $session = $this->get('session');
        if(!$session instanceof Session) $session = new Session();

        $value = $session->getId(); // get session id

        $session->set('foo','bar2');
        $foo = $session->get('foo', 'not exist');

        $response = $this->render('BookRequestBundle:Default:index.html.twig',array('name' => $foo));
        return $response;
    }

    // http://symfony2:8888/app_dev.php/book/flashbag
    private function exampleFlashBag() {
        $session = $this->get('session');
        if(!$session instanceof Session) $session = new Session();

        $session->getFlashBag()->add('notice', 'Your changes were saved!');

        $response = $this->render('BookRequestBundle:Example:flashbag.html.twig',array('name' => 'FlashBag'));
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
        return $response;
    }

    private function exampleJSONResponse($example) {
        switch ($example) {
            case '1':
                $response = new Response();
                $response->setContent(json_encode(array(
                    'data' => 123,
                )));
                $response->headers->set('Content-Type', 'application/json');
                break;
            case '2':
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

    private function exampleExpressionLanguage($example) {
        $language = new ExpressionLanguage();

        $examples[] = array('label'=>"evaluate('1 + 2'): ", 'value'=>$language->evaluate('1 + 2'));
        $examples[] = array('label'=>"compile('1 + 2'): ", 'value'=>$language->compile('1 + 2'));

        $apple = new Apple();
        $apple->variety = 'Honeycrisp';
        $apple->age = 34;
        $examples[] = array('label'=>"Accessing Public Properties: ", 'value'=>$language->evaluate('fruit.variety',array('fruit' => $apple,) ));

        $robot = new Robot();
        $examples[] = array('label'=>"Calling Methods: ", 'value'=>$language->evaluate('robot.sayHi(3)',array('robot' => $robot,)));

        define('DB_USER', 'root');
        $examples[] = array('label'=>"Constant: ", 'value'=>$language->evaluate('constant("DB_USER")'));

        $data = array('life' => 10, 'universe' => 10, 'everything' => 22);
        $examples[] = array('label'=>"Array: "  , 'value'=>$language->evaluate('data["life"] + data["universe"] + data["everything"]',array('data' => $data,)));
        $examples[] = array('label'=>"Array: "  , 'value'=>$language->evaluate('life + universe + everything',array('life' => 10,'universe' => 10,'everything' => 22,)));
        $examples[] = array('label'=>"matches: ", 'value'=>$language->evaluate('not ("foo" matches "/bar/")'));
        $examples[] = array('label'=>"compare: ", 'value'=>$language->evaluate('life == everything',array('life' => 10,'universe' => 10,'everything' => 22,)));
        $examples[] = array('label'=>"compare: ", 'value'=>$language->evaluate('life > everything',array('life' => 10,'universe' => 10,'everything' => 22,) ));
        $examples[] = array('label'=>"compare: ", 'value'=>$language->evaluate('life < universe or life < everything',array('life' => 10,'universe' => 10,'everything' => 22,)));
        $examples[] = array('label'=>"compare: ", 'value'=>$language->evaluate('firstName~" "~lastName',array('firstName' => 'Arthur','lastName' => 'Dent',)));
        $examples[] = array('label'=>"in: "     , 'value'=>$language->evaluate('fruit.variety in ["Honeycrisp", "Redapple"]',array('fruit' => $apple)));
        $examples[] = array('label'=>"in: "     , 'value'=>$language->evaluate('fruit.age in 18..45',array('fruit' => $apple,)));


        return $examples;
    }

}

class Robot {
    public function sayHi($times)
    {
        $greetings = array();
        for ($i = 0; $i < $times; $i++) {
            $greetings[] = 'Hi';
        }
        return implode(' ', $greetings).'!';
    }
}


class Apple {
    public $variety;
    public $age;
}