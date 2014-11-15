<?php
namespace FOA\Responder_Bundle;

use Aura\Accept\AcceptFactory;
use Aura\Web\WebFactory;
use FOA\DomainPayload\PayloadFactory;
use Twig_Environment;
use Twig_Loader_Array;
use FOA\Responder_Bundle\Renderer\Twig;

class TwigResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $response;

    protected $responder;

    public function setUp()
    {
        $web_factory = new WebFactory($GLOBALS);
        $this->response = $web_factory->newResponse();

        $accept_factory = new AcceptFactory($_SERVER);
        $accept = $accept_factory->newInstance();

        $loader = new Twig_Loader_Array(array(
            'hello' => 'Hello {{ name }}'
        ));
        $twig = new Twig_Environment($loader);
        $renderer = new Twig($twig);
        $this->responder = new FakeResponder($accept, $this->response, $renderer);
        $payload_factory = new PayloadFactory();
        $this->responder->setPayload($payload_factory->found(array('name' => 'Hari')));
    }

    public function testRenderView()
    {
        $this->responder->__invoke();
        $this->assertSame('Hello Hari', trim($this->response->content->get()));
    }
}
