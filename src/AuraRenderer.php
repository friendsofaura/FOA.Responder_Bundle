<?php
namespace FOA\Responder_Bundle;

class AuraRenderer implements RendererInterface
{
    protected $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function render($data, $view, $layout = null)
    {
        $this->view->setView($view);
        $this->view->setLayout($layout);
        $this->view->addData($data);
        return $this->view->__invoke();
    }
}
