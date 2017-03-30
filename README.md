# JSON API Standard implementation 

[![Build Status](https://scrutinizer-ci.com/g/mikemirten/JsonApi/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mikemirten/JsonApi/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/mikemirten/JsonApi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mikemirten/JsonApi/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mikemirten/JsonApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mikemirten/JsonApi/?branch=master)

This repository contains PHP-implementation of the [JsonAPI standard](http://jsonapi.org/).

An integration with the [Symfony Framework](https://symfony.com/) can be found inside of [JsonApi-Bundle repository](https://github.com/mikemirten/JsonApi-Bundle).

## How to install
Through composer:

```composer require mikemirten/json-api```

## How to use

The component contains a number of modules:
- Document
- Hydrator
- HTTP-Client
- Object mapper

### Document
The document represents the Json-API standard as a number of classes. These classes can be useful for a project work with requests and/or responses contains serialized data of Json-API standard.

The root of the document represented by four of them:

```Mikemirten\Component\JsonApi\Document\NoDataDocument```: Document contains no resources.

This kind of document usually can be used if there is no data for resource-object section.
```php
use Mikemirten\Component\JsonApi\Document\NoDataDocument;
use Mikemirten\Component\JsonApi\Document\LinkObject;
use Mikemirten\Component\JsonApi\Document\ErrorObject;

$document = new NoDataDocument();

// Passing of metadata
$document->setMetadataAttribute('time', '12345678');

// Passing of links
$link = new LinkObject('http://mydomain.com/myresource');
$link->setMetadataAttribute('methods', ['GET', 'POST']);
        
$document->setLink('self', $link);

// Passing of errors
$error = new ErrorObject();

$error->setCode('404');
$error->setTitle('Not found');
$error->setDetail('Resource identified by #123 not found');

$document->addError($error);

// Passing of included resources
$resource = new ResourceObject('456', 'Author');

$document->addIncludedResource($resource);
```

```Mikemirten\Component\JsonApi\Document\SingleResourceDocument```: Document contains single resource object.

Single-resource document has the same sections as no-data document but it must always contain a resource-object in data-section.

```php
use Mikemirten\Component\JsonApi\Document\ResourceObject;
use Mikemirten\Component\JsonApi\Document\SingleResourceDocument;

$resource = new ResourceObject('456', 'Author');        
$document = new SingleResourceDocument($resource);
```

```Mikemirten\Component\JsonApi\Document\ResourceCollectionDocument```: Document is a collection of resources. Can contain any amount of resources including nothing (empty collection).

```php
use Mikemirten\Component\JsonApi\Document\ResourceCollectionDocument;
use Mikemirten\Component\JsonApi\Document\ResourceObject;

$document = new ResourceCollectionDocument();

$resource = new ResourceObject('456', 'Author');
$document->addResource($resource);
```

```Mikemirten\Component\JsonApi\Document\AbstractDocument```: Base document for enlisted before. Cannot be instanciated, but can be used as an expected type when certain data structure is not known.

```php
use Mikemirten\Component\JsonApi\Document\AbstractDocument;

function handleMetadata(AbstractDocument $document)
{
    $document->setMetadataAttribute('server_time', microtime());
}
```

### Hydrator
The hydrator allows to create a structure of document by just decoded JSON.

### HTTP-Client
A simple HTTP-client compatible with PSR-7, handles bodies of requests and responses converting them to/from document.

### Object Mapper
Allows to map objects to a document's resources.
