<?php
namespace FOA\Responder_Bundle;

use Aura\Web\Response;
use Aura\Accept\Accept;
use FOA\DomainPayload\PayloadInterface;
use FOA\Responder_Bundle\Renderer\RendererInterface;

abstract class AbstractResponder
{
    protected $accept;

    protected $available = array();

    protected $response;

    protected $payload;

    protected $payload_method = array();

    protected $renderer;

    public function __construct(
        Accept $accept,
        Response $response,
        RendererInterface $renderer
    ) {
        $this->accept = $accept;
        $this->response = $response;
        $this->renderer = $renderer;
        $this->init();
    }

    protected function init()
    {
        if (! isset($this->payload_method['FOA\DomainPayload\Error'])) {
            $this->payload_method['FOA\DomainPayload\Error'] = 'error';
        }
    }

    public function __invoke()
    {
        $class = get_class($this->payload);
        $method = isset($this->payload_method[$class])
                ? $this->payload_method[$class]
                : 'notRecognized';
        $this->$method();
        return $this->response;
    }

    public function setPayload(PayloadInterface $payload)
    {
        $this->payload = $payload;
    }

    protected function notRecognized()
    {
        $domain_status = $this->getPayload('status');
        $this->response->status->set('500');
        $this->response->content->set("Unknown domain payload status: '$domain_status'");
        return $this->response;
    }

    protected function getPayload($key = null)
    {
        if ($this->payload) {
            return $this->payload->get($key);
        }
        return null;
    }

    protected function negotiateMediaType()
    {
        if (! $this->available || ! $this->accept) {
            return true;
        }

        $available = array_keys($this->available);
        $media = $this->accept->negotiateMedia($available);
        if (! $media) {
            $this->response->status->set(406);
            $this->response->content->setType('text/plain');
            $this->response->content->set(implode(',', $available));
            return false;
        }

        $this->response->content->setType($media->getValue());
        return true;
    }

    protected function renderView($view, $layout = null)
    {
        $data = $this->getPayload();
        $this->response->content->set($this->renderer->render($data, $view, $layout));
    }

    protected function notFound()
    {
        $this->response->status->set(404);
        $this->response->content->set('<html><head><title>Not found</title></head><body>Not found</body></html>');
    }

    protected function error()
    {
        $e = $this->getPayload('exception');
        $this->response->status->set('500');
        if ($e) {
            $message = $e->getMessage();
        } else {
            $message = "Internal server error";
        }
        $this->response->content->set($message);
    }
}
