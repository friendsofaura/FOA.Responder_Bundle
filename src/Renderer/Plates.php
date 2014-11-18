<?php
namespace FOA\Responder_Bundle\Renderer;

use League\Plates\Engine;

class Plates implements RendererInterface
{
    protected $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        return $this->engine->render($view, $data);
    }

    public function getEngine()
    {
        return $this->engine;
    }
}
