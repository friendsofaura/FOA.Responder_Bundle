<?php
namespace FOA\Responder_Bundle;

use Aura\Accept\AcceptFactory;
use Aura\Web\WebFactory;
use FOA\DomainPayload\PayloadFactory;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;
use FOA\Responder_Bundle\Renderer\Mustache;

class MustacheResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $response;

    protected $responder;

    public function setUp()
    {
        $web_factory = new WebFactory($GLOBALS);
        $this->response = $web_factory->newResponse();

        $accept_factory = new AcceptFactory($_SERVER);
        $accept = $accept_factory->newInstance();

        $mustache = new Mustache_Engine(array(
            'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/templates'),
        ));
        $renderer = new Mustache($mustache);

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
        $this->assertInstanceOf('FOA\Responder_Bundle\Renderer\Mustache', $this->responder->getRenderer());
    }

    public function testGetEngine()
    {
        $this->assertInstanceOf('Mustache_Engine', $this->responder->getRenderer()->getEngine());
    }
}
