<?php
namespace FOA\Responder_Bundle\Renderer;

interface RendererInterface
{
    /**
     * @return string
     */
    public function render($data, $view, $layout = null);

    public function getEngine();
}
