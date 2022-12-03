# symfony-request-param-bundle

## Goals

This bundle aims at reproduce the Java Spring's [Request Param annotation](https://www.baeldung.com/spring-request-param) but in PHP with Symfony.

With this bundle you can use PHP 8.1 native attribute to obtain the given result :

```php

#[Route('/demo', name: 'demo_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/{uid}', name: 'register', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam] RegisterRequest $registerRequest, ?string $uid = null): Response
    {
        dd($registerRequest);
    }
}

```

In our example,`$registerRequest` object will be built with the data in the request and validated.

## DtoRequestParam parameters

Several parameters are available for the **DtoRequestParam**, and it let you modify the behavior of the DTO injection.

- sourceType
- throwDeserializationException
- validateDto
- validationGroups
- throwValidationException

### sourceType
- string **sourceType**. Default value **SourceType::JSON**. This let you indicate the type of the input data. 

When you change this value, you must ensure that there is a **DtoProviderDriverInterface** that can supports that type of sourceType. Otherwise, you will get a **NoDtoProviderDriverFoundException**


A full description of the packaged sourceType is discussed later in this document.

Example :

```php
#[Route('/demo', name: 'demo_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/', name: 'register', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam(sourceType: SourceType::JSON)] RegisterRequest $registerRequest): Response
    {
        dd($registerRequest);
    }
    
    #[Route(path: '/xml', name: 'register_xml', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function registerXml(#[DtoRequestParam(sourceType: 'xml')] RegisterRequest $registerRequest): Response
    {
        dd($registerRequest);
    }
}

```


### throwDeserializationException
- bool **throwDeserializationException**. Default value **true**. 

If **true**, any exception during the deserialization phase is not captured and is rethrown.
If you turn this parameter to **false**, exception happening during the deserialization will be captured, logged, and **null** will be injected instead of the DTO.

Example :

```php
#[Route('/demo', name: 'demo_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/', name: 'register', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam(throwValidationException: true)] RegisterRequest $registerRequest): Response
    {
        // If something went bad during the deserialization, the exception is rethrown and this code will not be called...
        dd($registerRequest);
    }
    
    #[Route(path: '/test2', name: 'test2', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function test2(#[DtoRequestParam(throwValidationException: false)] ?RegisterRequest $registerRequest): Response
    {
        // Notice the type difference with the first method, we add "?RegisterRequest" because $registerRequest
        // will be null if there is a problem during the deserialization.
        dd($registerRequest); 
    }
}

```

### validateDto
- bool **validateDto**. Default value **true**. 

If **true**, a validation phase will be executed, using the [Symfony's validator](https://symfony.com/doc/current/validation.html).
If there is any contraint violation, the bundle will throw a custom exception and handle the error formatting and display (more on that later)

If **false**, no validation is done and your DTO will be injected in your controller's method right after the deserialization.


To set up your validation constraints you can use the official [Symfony's documentation](https://symfony.com/doc/current/validation.html#constraints) , but here is a glimpse :

````php
final class RegisterRequest
{
    #[NotBlank]
    private ?string $name = null;
    
    #[Positive]
    private ?int $age = null;
    
    // getters, setters, ...
}


````

Example :

```php
#[Route('/demo', name: 'demo_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/', name: 'register', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam(validateDto: true)] RegisterRequest $registerRequest): Response
    {
        dd($registerRequest); // My DTO is validated
    }
    
    #[Route(path: '/test2', name: 'test2', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function registerXml(#[DtoRequestParam(validateDto: false)] RegisterRequest $registerRequest): Response
    {
        dd($registerRequest); // No validation
    }
}

```

### validationGroups
- array|string **validationGroups**. Default value **['Default']**.

As this bundle use internally the Symfony's validator, we can specify a validation group to only validate a subset of our constraints.
You can learn more on that  [here](https://symfony.com/doc/current/validation/groups.html).

You can pass a single string, meaning only one validation group or an array of string, if you want to use many.

Here is something important to note, if **validateDto is true**, you can't give an empty array or string ([] or '') or you will get a **EmptyValidationGroupsException**.


Example :

```php
#[Route('/demo', name: 'demo_')]
class RegisterController extends AbstractApiController
{
    #[Route(path: '/', name: 'register', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function register(#[DtoRequestParam(validationGroups: 'register-validation-1')] RegisterRequest $registerRequest): Response
    {
        dd($registerRequest); 
    }
    
    #[Route(path: '/test2', name: 'test2', methods: ['POST'])]
    #[AutoProvideRequestDto]
    public function registerXml(#[DtoRequestParam(validationGroups: ['register-validation-1', 'register-validation-2'])] RegisterRequest $registerRequest): Response
    {
        dd($registerRequest); 
    }
}
```