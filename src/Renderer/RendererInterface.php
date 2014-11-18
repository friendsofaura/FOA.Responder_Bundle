<?php
namespace FOA\Responder_Bundle\Renderer;

interface RendererInterface
{
    public function render($data, $view, $layout = null);

    public function getEngine();
}
