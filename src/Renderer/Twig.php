<?php
namespace FOA\Responder_Bundle\Renderer;

use Twig_Environment;

class Twig implements RendererInterface
{
    protected $engine;

    public function __construct(Twig_Environment $engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        return $this->engine->render($view, $data);
    }
}
