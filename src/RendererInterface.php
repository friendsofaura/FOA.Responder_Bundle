<?php
namespace FOA\Responder_Bundle;

interface RendererInterface
{
    public function render($data, $view, $layout = null);
}
