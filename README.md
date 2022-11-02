# symfony-request-param-bundle

## Goals

This bundle aims at reproduce the Java Spring's [Request Param annotation](https://www.baeldung.com/spring-request-param) but in PHP with Symfony.

With this bundle you can use PHP 8.1 native attribute to obtain the given result :

```php

#[Route('/demo', name: 'demo_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/', name: 'post')]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam] RegisterRequest $registerRequest, ?string $uid = null): Response
    {
        dd($registerRequest)
    }
}

```

In our example,`$registerRequest` object will be built with the data in the request and validated.