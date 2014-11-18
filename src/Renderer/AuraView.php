<?php
namespace FOA\Responder_Bundle\Renderer;

use Aura\View\View;

class AuraView implements RendererInterface
{
    protected $engine;

    public function __construct(View $engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        $this->engine->setView($view);
        $this->engine->setLayout($layout);
        $this->engine->addData($data);
        return $this->engine->__invoke();
    }

    public function getEngine()
    {
        return $this->engine;
    }
}
