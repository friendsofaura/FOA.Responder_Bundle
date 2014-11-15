<?php
namespace FOA\Responder_Bundle;

class FakeResponder extends AbstractResponder
{
    protected $available = array(
        'text/html' => '',
        'application/json' => '.json',
    );

    protected $payload_method = array(
        'FOA\DomainPayload\Found' => 'display',
        'FOA\DomainPayload\NotFound' => 'notFound',
        'FOA\DomainPayload\Valid' => 'valid',
        'FOA\DomainPayload\NotValid' => 'notValid',
    );

    public function display()
    {
        if ($this->negotiateMediaType()) {
            $view = 'hello';
            $content_type = $this->response->content->getType();
            if ($content_type) {
                $view .= $this->available[$content_type];
            }
            $this->renderView($view);
        }
    }
}
