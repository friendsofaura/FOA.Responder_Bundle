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
        if ($layout === null) {
            return $this->engine->render($view, $data);
        }

        $engine = $this->engine;
        $data['content'] = function () use ($engine, $view, $data) {
            return $engine->render($view, $data);
        };

        return $this->engine->render($layout, $data);
    }
}
