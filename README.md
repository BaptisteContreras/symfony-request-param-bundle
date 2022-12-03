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

## Sources available

For the moment only the **json** source is supported. You can extend the bundle by creating a **DtoProviderDriverInterface** for your needs.
Any object that implements this interface will be used internally without any additional action.

When your custom provider returns true if its **supports** method is called, it will be selected for the deserialization.

Here is an example for an XML provider.

You can play with the tag priority of you custom provider if you want to ensure that it's called first. [Here is the documentation about that](https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority)

**request_param.dto-provider-driver** is the tag associated with the **DtoProviderDriverInterface**.

An example if you need to modify the priority of your custom provider

```yaml
# services.yaml

    App\CustomProvider:
        class: 'App\CustomProvider'
        tags:
            - { name: "request_param.dto-provider-driver", priority: 20 }
```

```php
class CustomXmlProvider implements DtoProviderDriverInterface
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function fromRequest(DtoProviderContext $context, Request $request): mixed
    {
        try {
            // The Symfony's serialize is used here but feel free to handle the raw data your way ! 
            return $this->serializer->deserialize($request->getContent(), $context->getDtoClass(), 'xml', []);
        } catch (\Throwable $exception) {
            // This is optional, but you should do it, otherwise the 
            // throwDeserializationException parameter will be useless...
            if ($context->shouldThrowDeserializationException()) {
                throw $exception;
            }

            return null;
        }
    }

    public function supports(DtoProviderContext $dtoProviderContext): bool
    {
        return 'xml' === $dtoProviderContext->getSourceType(); // You can add more logic if needed
    }
}
```

### JsonDtoProvider

If you specify **SourceType::JSON** as the sourceType, the **JsonDtoProvider** service will be used.

Internally it use the [Symfony's serializer](https://symfony.com/doc/current/components/serializer.html)

For the moment a very basic configuration of the serializer is used, and it populates your DTOs using setters.

Later it will be possible to change that. (Actually you can change that by configuring the serializer component your way, [more reading here](https://symfony.com/doc/current/serializer.html))


## Error Handling

### Presenter

An error presenter is a service responsible to return a response in a predefined format. For example json error format :

```json
{
  "error": true,
  "status": 400,
  "message": "Bad request"
}
```

If you want to add your own error presenter, which return a custom format you just have to create an object that implements **ErrorPresenterDriverInterface**.

Like the custom provider above, no further actions are required (i.e. if your custom error presenter's **supports** method returns true, it will be used !).

You must be aware that the first error handler register which returns **true** will be use. As for the providers, you can play with the tag priority to put your handler first in the selection chain [Here is the documentation about that](https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority).

**request_param.error-presenter-driver** is the tag associated with the **ErrorPresenterDriverInterface**.

An example if you need to modify the priority of your custom provider

```yaml
# services.yaml

    App\CustomErrorPresenter:
        class: 'App\CustomErrorPresenter'
        tags:
            - { name: "request_param.error-presenter-driver", priority: 20 }
```

When you create your own presenter, in addition of the **supports** method, you must implement two important methods :

- **presentBadRequest** : is called for a validation exception or an HTTP 400 error
- **presentTechnicalError** : is called in any other case

Here is an example of a custom basic HTML error presenter :

```php
class BasicHtmlErrorPresenter implements ErrorPresenterDriverInterface
{

    public function presentBadRequest(RequestDtoException $requestDtoException, Request $request): Response
    {
        return new Response('<html><body><h1>Bad request</h1></body></html>', 400);
    }

    public function presentTechnicalError(RequestDtoException $requestDtoException, Request $request): Response
    {
        return return new Response('<html><body><h1>Technical error</h1></body></html>', 500);
    }

    public function supports(RequestDtoException $requestDtoException, Request $request): bool
    {
        return $request->headers->has('....'); // Your logic here
    }
}

```

#### JsonErrorPresenter

By default, the JsonErrorPresenter service will be used to returns a JSON response with the error detailed.

Here is an example of 2 responses :

- Given a bad request, it will produce

```json

{
  "error": true,
  "success": false,
  "message": "Bad request",
  "status": 400,
  "errors": [
    "[property_1] : Should not be blank"
  ]
}

```

- Given any other error, the result will be :

```json

{
  "error": true,
  "success": false,
  "message": "Technical error",
  "status": 500
}

```

You can easily modify this response format. In fact, this presenter use 1 decorator stack to create a response array.


[Symfony's decorator](https://symfony.com/doc/current/service_container/service_decoration.html)


[Symfony's decorator stack](https://symfony.com/doc/current/service_container/service_decoration.html#stacking-decorators)


**JsonErrorPresenter::presentBadRequest** uses the  **stack_response_formatter_json_bad_request** stack.
**JsonErrorPresenter::presentTechnicalError** uses the  **stack_response_formatter_json_technical_error** stack.

Both stacks above are a sequence of **JsonFormatterInterface** applied one by one. 

Lets look at the **stack_response_formatter_json_bad_request** definition :


```xml
<stack id="stack_response_formatter_json_bad_request">
    <service parent="stack_response_formatter_json_bad_request_default"/> <!--  It's a little trick to easily append a new formatter to the defaults defined in stack_response_formatter_json_bad_request_default -->
</stack>

<stack id="stack_response_formatter_json_bad_request_default">
    <service alias="request_param.response.formatter.json.validation" />
    <service alias="request_param.response.formatter.json.default" />
</stack>
```

Let's create a new formatter to add a new ```"test": "ok" ``` key, and, lets say, remove the ```"success": false``` key


```php

class CustomJsonFormatter implements JsonFormatterInterface
{
    // $this->decorated is the next formatter in the chain (i.e. the one we decorate with our custom formatter)
    // It can be null if our formatter is the last to be called
    // the order depends on the stack definition you made in your services.yaml
    public function __construct(private readonly ?JsonFormatterInterface $decorated = null)
    {
    }

    public function format(array $currentResponse, RequestDtoException $requestDtoException, Request $request, string $defaultMessage, int $httpCode): array
    {
        $currentResponse['test'] = 'ok';

        if ($this->decorated) {
            $currentResponse = $this->decorated->format(
                $currentResponse, $requestDtoException, $request, $defaultMessage, $httpCode
            );
            
            unset($currentResponse['success']);
        }

        return $currentResponse;
    }
}
```

We need to add a little more configuration in the **services.yaml**

```yaml

  App\CustomJsonFormatter:
    class: 'App\CustomJsonFormatter'

  
  stack_response_formatter_json_bad_request:
    stack:
      - App\CustomJsonFormatter: ~
      - alias: stack_response_formatter_json_bad_request_default  
      # In this configuration, our formatter is the first in the chain, and we include the default chain
      # stack_response_formatter_json_bad_request_default is an alias for request_param.response.formatter.json.validation AND request_param.response.formatter.json.default  
```

And voila 

```json
{
  "test": "ok",
  "error": true,
  "success": false,
  "message": "Bad request",
  "errors": [
    "[property_1] : Should not be blank"
  ]
}
```

With this decorator approache you can really customize the json response easily


```yaml

  App\CustomJsonFormatter:
    class: 'App\CustomJsonFormatter'

  
  stack_response_formatter_json_bad_request:
    stack:
      - App\CustomJsonFormatter: ~
      - alias: request_param.response.formatter.json.validation
      # In this configuration, our formatter is the first in the chain, and we only include the "validation formatter"
    
```

Will produce:

```json
{
  "test": "ok",
  "errors": [
    "[property_1] : Should not be blank"
  ]
}
```


By default, **request_param.response.formatter.json.validation** is responsible for the **errors** key and **request_param.response.formatter.json.default** the others you saw in the above examples.