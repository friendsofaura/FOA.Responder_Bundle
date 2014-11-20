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
        $renderer->setTemplateExtension('smarty');
        $renderer->setLayoutVariableName('the_content');

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

    public function testTemplateExtension()
    {
        $this->assertSame('smarty', $this->responder->getRenderer()->getTemplateExtension());
        $this->responder->getRenderer()->setTemplateExtension('.tpl');
        $this->assertSame('tpl', $this->responder->getRenderer()->getTemplateExtension());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLayoutVariableName()
    {
        $this->assertSame('the_content', $this->responder->getRenderer()->getLayoutVariableName());
        $this->responder->getRenderer()->setLayoutVariableName('content');
        $this->assertSame('content', $this->responder->getRenderer()->getLayoutVariableName());
        $this->responder->getRenderer()->setLayoutVariableName('0_content');
    }

    public function testRenderWithTwoStepView()
    {
        $data = array(
            'name' => 'Hari'
        );
        $view = 'hello';
        $layout = 'layout';
        $this->assertSame(
            '<!doctype html><html><head><meta charset="utf-8"><title>test layout</title></head><body><h1>Hello Hari</h1></body></html>',
            $this->responder->getRenderer()->render($data, $view, $layout)
        );
    }
}
