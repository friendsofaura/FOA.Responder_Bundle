<?php
namespace FOA\Responder_Bundle\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    public function define(Container $di)
    {
        $di->params['FOA\Responder_Bundle\AbstractResponder']['response'] = $di->lazyGet('aura/web-kernel:response');
        $di->params['FOA\Responder_Bundle\AbstractResponder']['view'] = $di->lazyNew('Aura\View\View');
        $di->params['FOA\Responder_Bundle\AbstractResponder']['accept'] = $di->lazyNew('Aura\Accept\Accept');
    }

    public function modify(Container $di)
    {
    }
}
