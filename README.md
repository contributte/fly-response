![](https://heatbadger.now.sh/github/readme/contributte/fly-response/?deprecated=1)

<p align=center>
    <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
    <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
    <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
    Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

## Disclaimer

| :warning: | This project is no longer being maintained. Please use [contributte/application](https://github.com/contributte/application).
|---|---|

| Composer | [`contributte/fly-response`](https://packagist.org/packages/contributte/fly-response) |
|---| --- |
| Version | ![](https://badgen.net/packagist/v/contributte/fly-response) |
| PHP | ![](https://badgen.net/packagist/php/contributte/fly-response) |
| License | ![](https://badgen.net/github/license/contributte/fly-response) |

## Documentation

### Usage

#### Responses

##### FlyResponse

For common purpose and your custom solutions.

##### FlyFileResponse

Special response for handling files on-the-fly.

#### Adapters

##### ProcessAdapter

Execute command over [popen](http://php.net/manual/en/function.popen.php).

```php
use Minetro\FlyResponse\Adapter\ProcessAdapter;
use Minetro\FlyResponse\FlyFileResponse;

// Compress current folder and send to response
$adapter = new ProcessAdapter('tar cf - ./ | gzip -c -f');
$response = new FlyFileResponse($adapter, 'folder.tgz');

$this->sendResponse($response);
```

##### StdoutAdapter

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

##### CallbackAdapter

```php
use Minetro\FlyResponse\Adapter\CallbackAdapter;
use Minetro\FlyResponse\Buffer\Buffer;
use Minetro\FlyResponse\FlyFileResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

$adapter = new CallbackAdapter(function(IRequest $request, IResponse $response) use ($model) {
	// Modify headers
	$response->setHeader($header);

	// Fetch topsecret data
	$data = $this->facade->getData();
	foreach ($data as $d) {
		// Write or print data..
	}
});
$response = new FlyFileResponse($adapter, 'my.data');

$this->sendResponse($response);
```

#### Model

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

## Development

This package was maintain by these authors.

<a href="https://github.com/f3l1x">
  <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team.
Also thank you for being used this package.
