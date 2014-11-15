<?php
namespace FOA\Responder_Bundle;

use Aura\Accept\AcceptFactory;
use Aura\Html\HelperLocatorFactory;
use Aura\View\ViewFactory;
use Aura\Web\WebFactory;
use FOA\DomainPayload\PayloadFactory;

class ResponderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $web_factory = new WebFactory($GLOBALS);
        $this->response = $web_factory->newResponse();

        $accept_factory = new AcceptFactory($_SERVER);
        $accept = $accept_factory->newInstance();

        $factory = new HelperLocatorFactory;
        $helpers = $factory->newInstance();
        $view_factory = new ViewFactory();
        $view = $view_factory->newInstance($helpers);
        $view_registry = $view->getViewRegistry();
        $view_registry->set('hello', function () {
            echo "Hello " . $this->name;
        });

        $view_registry->set('hello.json', function () {
            echo json_encode(array("Hello" => $this->name));
        });
        $renderer = new AuraRenderer($view);
        $this->responder = new FakeResponder($accept, $this->response, $renderer);
    }

    public function testNotRecognized()
    {
        $this->responder->__invoke();
        $this->assertSame(500, $this->response->status->getCode());
        $this->assertSame('Unknown domain payload status: \'\'', $this->response->content->get());
    }

    public function testNegotiateMediaType()
    {
        $payload_factory = new PayloadFactory();
        $this->responder->setPayload($payload_factory->found(array('name' => 'Hari')));
        $this->responder->__invoke();
        $this->assertSame('Hello Hari', $this->response->content->get());
    }

    public function testNotFound()
    {
        $payload_factory = new PayloadFactory();
        $this->responder->setPayload($payload_factory->notFound(array()));
        $this->responder->__invoke();
        $this->assertSame(404, $this->response->status->getCode());
        $this->assertSame('<html><head><title>Not found</title></head><body>Not found</body></html>', $this->response->content->get());
    }

    public function testNegotiateMediaTypeNotAvailable()
    {
        $this->markTestSkipped();
        $web_factory = new WebFactory($GLOBALS);
        $response = $web_factory->newResponse();

        $accept_factory = new AcceptFactory(array(
            'HTTP_ACCEPT' => 'text/plain',
        ));
        $accept = $accept_factory->newInstance();

        $factory = new HelperLocatorFactory;
        $helpers = $factory->newInstance();
        $view_factory = new ViewFactory();
        $view = $view_factory->newInstance($helpers);
        $view_registry = $view->getViewRegistry();
        $view_registry->set('hello', function () {
            echo "Hello " . $this->name;
        });

        $view_registry->set('hello.json', function () {
            echo json_encode(array("Hello" => $this->name));
        });
        $renderer = new AuraRenderer($view);
        $responder = new FakeResponder($accept, $response, $renderer);
        $payload_factory = new PayloadFactory();
        $responder->setPayload($payload_factory->found(array()));
        $responder->__invoke();
        $this->assertSame(406, $this->response->status->getCode());
        $this->assertSame('text/html, application/json', $this->response->content->get());
    }
}
