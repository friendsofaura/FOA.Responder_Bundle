<?php
namespace FOA\Responder_Bundle;

use Aura\Accept\AcceptFactory;
use Aura\Web\WebFactory;
use FOA\DomainPayload\PayloadFactory;
use Smarty;
use FOA\Responder_Bundle\Renderer\Smarty as SmartyEngine;

class SmartyResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $response;

    protected $responder;

    public function setUp()
    {
        $web_factory = new WebFactory($GLOBALS);
        $this->response = $web_factory->newResponse();

        $accept_factory = new AcceptFactory($_SERVER);
        $accept = $accept_factory->newInstance();

        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__.'/templates');
        $renderer = new SmartyEngine($smarty);
        $renderer->setTemplateExtension('tpl');

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
        $this->assertInstanceOf('FOA\Responder_Bundle\Renderer\Smarty', $this->responder->getRenderer());
    }

    public function testGetEngine()
    {
        $this->assertInstanceOf('Smarty', $this->responder->getRenderer()->getEngine());
    }
}
