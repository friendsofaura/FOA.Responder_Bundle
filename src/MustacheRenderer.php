<?php
namespace FOA\Responder_Bundle;

class MustacheRenderer implements RendererInterface
{
    protected $engine;

    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        $tpl = $this->engine->loadTemplate($view);
        return $tpl->render($data);
    }
}
