# FOA.Resonder_Bundle

AbstractResponder of [action domain responder](https://github.com/pmjones/adr) by Paul M Jones.

## Foreword

### Installation

It is installable and autoloadable via composer as [foa/responder-bundle](https://packagist.org/packages/foa/responder-bundle).

### Quality

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/friendsofaura/FOA.Responder_Bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/friendsofaura/FOA.Responder_Bundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/friendsofaura/FOA.Responder_Bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/friendsofaura/FOA.Responder_Bundle/)
[![Build Status](https://travis-ci.org/friendsofaura/FOA.Responder_Bundle.png?branch=master)](https://travis-ci.org/friendsofaura/FOA.Responder_Bundle)

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

### Community

To ask questions, provide feedback, or otherwise communicate with the Aura community, please join our [Google Group](http://groups.google.com/group/auraphp), follow [@auraphp on Twitter](http://twitter.com/auraphp), or chat with us on #auraphp on Freenode.

## Example usage

It is recommend to checkout the [action domain responder](https://github.com/pmjones/adr) example code.

> It is recommended you use some sort of DI containers.

```php
<?php
namespace FOA\Responder_Bundle;

class BrowseResponder extends AbstractResponder
{
    protected $available = array(
        'text/html' => '',
        'application/json' => '.json',
    );

    protected $payload_method = array(
        'FOA\DomainPayload\Found' => 'display',
        'FOA\DomainPayload\NotFound' => 'notFound',
        'FOA\DomainPayload\Valid' => 'valid',
        'FOA\DomainPayload\NotValid' => 'notValid',
    );

    public function display()
    {
        if ($this->negotiateMediaType()) {
            $content_type = $this->response->content->getType();
            if ($content_type) {
                $view .= $this->available[$content_type];
            }
            $this->renderView('browse', 'layout');
        }
    }
}
```

First instantiate your templating engine of choice. Eg usage of Aura.View .

```php
<?php
// Instantiate your templating engine
use Aura\Html\HelperLocatorFactory;
use Aura\View\ViewFactory;
use FOA\Responder_Bundle\Renderer\AuraView;

$factory = new HelperLocatorFactory;
$helpers = $factory->newInstance();

$view_factory = new ViewFactory();
$view = $view_factory->newInstance($helpers);

$renderer = new AuraView($view);
```

### Create your responder object

```php
<?php
use Aura\Web\Response;
use Aura\Accept\Accept;

$web_factory = new WebFactory($GLOBALS);
$response = $web_factory->newResponse();

$accept_factory = new AcceptFactory($_SERVER);
$accept = $accept_factory->newInstance();

$responder = new BrowseResponder($accept, $response, $renderer);
```

### Rendering and Setting content to response

```
<?php
$responder->__invoke();
```

### Inside Aura Project

Add the configuration as

```php
$di->params['FOA\Responder_Bundle\AbstractResponder']['response'] = $di->lazyGet('aura/web-kernel:response');
$di->params['FOA\Responder_Bundle\AbstractResponder']['view'] = $di->lazyNew('Aura\View\View');
$di->params['FOA\Responder_Bundle\AbstractResponder']['accept'] = $di->lazyNew('Aura\Accept\Accept');
```

## Integrated Views

We have integrated the below template engines. You can choose the one you love.

1. [aura/view](https://github.com/auraphp/Aura.View)
1. [league/plates](https://github.com/thephpleague/Plates)
1. [mustache/mustache](https://github.com/bobthecow/mustache.php)
1. [twig/twig](https://github.com/twigphp/Twig)

