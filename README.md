# FOA.Resonder_Bundle

AbstractResponder of [action domain responder](https://github.com/pmjones/adr) by Paul M Jones.

## Foreword

### Installation

It is installable and autoloadable via composer as [foa/responder-bundle](https://packagist.org/packages/foa/responder-bundle).


```bash
composer require foa/responder-bundle
```

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

It is recommend you first go through the [action domain responder](https://pmjones.github.io/adr/) paper and [example code](https://github.com/pmjones/adr/tree/master/example-code).

In this examples we are using few aura components, but `foa/responder-bundle` is not specifically for Aura framework. You can integrate in any framework if you like the concept using its classes.

Consider you are having a blog application, which you can browse the posts. Let us see how it looks in ADR. You should pay special attention to responder only. Action and Service layer components may differ with what you are using.

### Action

```php
<?php
// BlogBrowseAction.php
namespace Vendor\Blog\Web\Action;

use FOA\DomainPayload\PayloadFactory;
use Vendor\Blog\Web\Responder\BlogBrowseResponder;

class BlogBrowseAction
{
    protected $domain;
    protected $responder;

    public function __construct(
        BlogService $domain,
        BlogBrowseResponder $responder
    ) {
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function __invoke()
    {
        $payload_factory = new PayloadFactory();
        $payload = $payload_factory->found(
            array(
                array('title' => 'Some awesome title', 'author' => 'Hari KT'),
                array('title' => 'Some awesome post', 'author' => 'Paul M Jones'),
                array('title' => 'Some awesome content', 'author' => 'Justin'),
            )
        );
        // Rather than the above code, you should actually do something like
        // $payload = $this->domain->fetchPage($page, $paging);
        $this->responder->setPayload($payload);
        return $this->responder->__invoke();
    }
}
```

### Responder

```php
<?php
// BlogBrowseResponder.php
namespace Vendor\Blog\Web\Responder;

use FOA\Responder_Bundle\AbstractResponder;

class BlogBrowseResponder extends AbstractResponder
{
    protected $available = array(
        'text/html' => '',
        'application/json' => '.json',
    );

    protected $payload_method = array(
        'FOA\DomainPayload\Found' => 'display'
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

First instantiate your templating engine of choice. Eg usage with [Aura.View](https://github.com/auraphp/Aura.View). See [other templating engines supported below](https://github.com/friendsofaura/FOA.Responder_Bundle#integrated-templating-engines).

```php
<?php
$factory = new \Aura\Html\HelperLocatorFactory;
$helpers = $factory->newInstance();

$engine = new \Aura\View\View(
    new \Aura\View\TemplateRegistry,
    new \Aura\View\TemplateRegistry,
    $helpers
);

$renderer = new \FOA\Responder_Bundle\Renderer\AuraView($engine);
```

### Create your responder object

```php
<?php
$web_factory = new \Aura\Web\WebFactory($GLOBALS);
$response = $web_factory->newResponse();

$accept_factory = new \Aura\Accept\AcceptFactory($_SERVER);
$accept = $accept_factory->newInstance();

$responder = new \Vendor\Blog\Web\Responder\BlogBrowseResponder($accept, $response, $renderer);
```

### Rendering and Setting content to response

```php
<?php
use FOA\DomainPayload\PayloadFactory;

$payload_factory = new PayloadFactory();
$payload = $payload_factory->found(array('name' => 'Hari KT'));
$responder->setPayload($payload);

$responder->__invoke();
```

Calling `__invoke` will render and set the content on the response object. Now you can either use `Aura\Web\ResponseSender` to send the response, or get the headers from response object and set to your favourite library response.

```php
<?php
echo $response->content->get();
```

## Aura framework integration with Aura.Di

In your project `config/Common.php` define method add the below lines.

```php
$di->params['FOA\Responder_Bundle\Renderer\AuraView']['engine'] = $di->lazyNew('Aura\View\View');
// responder
$di->params['FOA\Responder_Bundle\AbstractResponder']['response'] = $di->lazyGet('aura/web-kernel:response');
$di->params['FOA\Responder_Bundle\AbstractResponder']['renderer'] = $di->lazyNew('FOA\Responder_Bundle\Renderer\AuraView');
$di->params['FOA\Responder_Bundle\AbstractResponder']['accept'] = $di->lazyNew('Aura\Accept\Accept');
```

> Don't forget to change the `renderer` with the one you like.

## Integrated templating engines

Responder bundle integrates below templating engines. Feel free to choose the one you love.

1. [aura/view](https://github.com/auraphp/Aura.View)
1. [league/plates](https://github.com/thephpleague/Plates)
1. [mustache/mustache](https://github.com/bobthecow/mustache.php)
1. [twig/twig](https://github.com/twigphp/Twig)

## Integrating other templating engines

```php
<?php
namespace FOA\Responder_Bundle\Renderer;

use FOA\Responder_Bundle\Renderer\RendererInterface;
use Your\TemplateEngine;

class YourTemplateEngine implements RendererInterface
{
    public function __construct(TemplateEngine $engine)
    {
        $this->engine = $engine;
    }

    public function render($data, $view, $layout = null)
    {
        // according to how the rendering engine works. See other implementations
        // $this->engine->render
    }
}
```

Yes, and we love tests!.
