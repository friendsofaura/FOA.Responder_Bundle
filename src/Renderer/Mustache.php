<?php
namespace FOA\Responder_Bundle\Renderer;

use Mustache_Engine;

class Mustache implements RendererInterface
{
    protected $engine;

    public function __construct(Mustache_Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        $tpl = $this->engine->loadTemplate($view);
        return $tpl->render($data);
    }
}
