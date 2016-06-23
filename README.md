# FlyResponse

On-the-fly response for Nette Framework.

-----

[![Build Status](https://img.shields.io/travis/minetro/fly-response.svg?style=flat-square)](https://travis-ci.org/minetro/fly-response)
[![Code coverage](https://img.shields.io/coveralls/minetro/fly-response.svg?style=flat-square)](https://coveralls.io/r/minetro/fly-response)
[![Downloads this Month](https://img.shields.io/packagist/dm/minetro/fly-response.svg?style=flat-square)](https://packagist.org/packages/minetro/fly-response)
[![Downloads total](https://img.shields.io/packagist/dt/minetro/fly-response.svg?style=flat-square)](https://packagist.org/packages/minetro/fly-response)
[![Latest stable](https://img.shields.io/packagist/v/minetro/fly-response.svg?style=flat-square)](https://packagist.org/packages/minetro/fly-response)
[![HHVM Status](https://img.shields.io/hhvm/minetro/fly-response.svg?style=flat-square)](http://hhvm.h4cc.de/package/minetro/fly-response)

## Discussion / Help

[![Join the chat](https://img.shields.io/gitter/room/minetro/nette.svg?style=flat-square)](https://gitter.im/minetro/nette?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Install

```sh
composer require minetro/fly-response
```

## Responses

### FlyResponse

For common purpose and your custom solutions.

### FlyFileResponse

Special response for handling files on-the-fly.

## Adapters

### ProcessAdapter

Execute command over [popen](http://php.net/manual/en/function.popen.php).

```php
use Minetro\FlyResponse\Adapter\ProcessAdapter;
use Minetro\FlyResponse\FlyFileResponse;

// Compress current folder and send to response
$adapter = new ProcessAdapter('tar cf - ./ | gzip -c -f');
$response = new FlyFileResponse($adapter, 'folder.tgz');

$this->sendResponse($response);
```

### StdoutAdapter

Write to `php://output`.

```php
use Minetro\FlyResponse\Adapter\StdoutAdapter;
use Minetro\FlyResponse\Buffer\Buffer;
use Minetro\FlyResponse\FlyFileResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

// Write to stdout over buffer class
$adapter = new StdoutAdapter(function(Buffer $buffer, IRequest $request, IResponse $response) {
    // Modify headers
    $response->setHeader(..);
    
    // Write data
    $buffer->write('Some data..');
});
$response = new FlyFileResponse($adapter, 'my.data');

$this->sendResponse($response);
```

### CallbackAdapter

```php
use Minetro\FlyResponse\Adapter\CallbackAdapter;
use Minetro\FlyResponse\Buffer\Buffer;
use Minetro\FlyResponse\FlyFileResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

$adapter = new CallbackAdapter(function(IRequest $request, IResponse $response) use ($model) {
    // Modify headers
    $response->setHeader(..);
    
    // Fetch topsecret data
    $data = $this->facade->getData();
    foreach ($data as $d) {
        // Write or print data..
    }
});
$response = new FlyFileResponse($adapter, 'my.data');

$this->sendResponse($response);
```

## Model

```php
final class BigOperationHandler
{

    /** @var Facade */
    private $facade;

    /**
     * @param Facade $facade
     */
    public function __construct(Facade $facade)
    {
        $this->facade = $facade;
    }

    public function toFlyResponse()
    {
        $adapter = new CallbackAdapter(function (IRequest $request, IResponse $response) {
            // Modify headers
            $response->setHeader(..);

            // Fetch topsecret data
            $data = $this->facade->getData();
            foreach ($data as $d) {
                // Write or print data..
            }
        });

        return new FlyFileResponse($adapter, 'file.ext');

        // or
        return new FlyResponse($adapter);
    }
}

interface IBigOperationHandlerFactory
{

    /**
     * @return BigOperationHandler
     */
    public function create();

}

final class MyPresenter extends Nette\Application\UI\Presenter
{

    /** @var IBigOperationHandlerFactory @inject */
    public $bigOperationHandlerFactory;

    public function handleMagic()
    {
        $this->sendResponse(
            $this->bigOperationHandlerFactory->create()->toFlyResponse()
        );
    }
}
```

-----

Thanks for testing, reporting and contributing.
