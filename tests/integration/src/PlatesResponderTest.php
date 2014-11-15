<?php
namespace FOA\Responder_Bundle;

use Aura\Accept\AcceptFactory;
use Aura\Web\WebFactory;
use FOA\DomainPayload\PayloadFactory;
use League\Plates\Engine;

class PlatesResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $response;

    protected $responder;

    public function setUp()
    {
        $web_factory = new WebFactory($GLOBALS);
        $this->response = $web_factory->newResponse();

        $accept_factory = new AcceptFactory($_SERVER);
        $accept = $accept_factory->newInstance();

        $templates = new Engine(__DIR__ . '/templates');
        $renderer = new PlatesRenderer($templates);

        $this->responder = new FakeResponder($accept, $this->response, $renderer);

        $payload_factory = new PayloadFactory();
        $this->responder->setPayload($payload_factory->found(array('name' => 'Hari')));
    }

    public function testRenderView()
    {
        $this->responder->__invoke();
        $this->assertSame('Hello Hari', $this->response->content->get());
    }
}
