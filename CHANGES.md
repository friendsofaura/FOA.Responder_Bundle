# 0.3.0 : 18 Nov 2014

1. BC : Added `getEngine()` to `FOA\Responder_Bundle\Renderer\RendererInterface`.
1. Added `getRenderer()` to `FOA\Responder_Bundle\AbstractResponder`.

# 0.2.0 : 15 Nov 2014

1. Changed to make use of `FOA\Responder_Bundle\Renderer\RendererInterface`.
1. `phpunit.xml` moved to root of project.
1. Added `FOA\Responder_Bundle\FakeResponder` which extends `FOA\Responder_Bundle\AbstractResponder` for unit testing.
1. Added `AuraView` to render [aura/view](https://github.com/auraphp/Aura.View)
1. Added `Plates` to render [league/plates](https://github.com/thephpleague/Plates)
1. Added `Mustache` to render [mustache/mustache](https://github.com/bobthecow/mustache.php)
1. Added `Twig` to render [twig/twig](https://github.com/twigphp/Twig)
1. Removed `config/Common.php` due to shared usage of response if needed. So add in your project config for aura projects.

## Todo
1. Need a test for `testNegotiateMediaTypeNotAvailable()` .
