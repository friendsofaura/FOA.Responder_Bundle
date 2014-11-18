<?php
namespace FOA\Responder_Bundle;

use Aura\Accept\AcceptFactory;
use Aura\Web\WebFactory;
use FOA\DomainPayload\PayloadFactory;
use League\Plates\Engine;
use FOA\Responder_Bundle\Renderer\Plates;

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
        $renderer = new Plates($templates);

        $this->responder = new FakeResponder($accept, $this->response, $renderer);

        $payload_factory = new PayloadFactory();
        $this->responder->setPayload($payload_factory->found(array('name' => 'Hari')));
    }

    public function testRenderView()
    {
        $this->responder->__invoke();
        $this->assertSame('Hello Hari', trim($this->response->content->get()));
    }

    public function testGetRenderer()
    {
        $this->assertInstanceOf('FOA\Responder_Bundle\Renderer\Plates', $this->responder->getRenderer());
    }

    public function testGetEngine()
    {
        $this->assertInstanceOf('League\Plates\Engine', $this->responder->getRenderer()->getEngine());
    }
}
