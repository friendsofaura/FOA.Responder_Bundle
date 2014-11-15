<?php
namespace FOA\Responder_Bundle;

class PlatesRenderer implements RendererInterface
{
    protected $engine;

    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        return $this->engine->render($view, $data);
    }
}
