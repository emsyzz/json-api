# JSON API Standard implementation 

[![Build Status](https://scrutinizer-ci.com/g/mikemirten/JsonApi/badges/build.png?b=master)](https://scrutinizer-ci.com/g/mikemirten/JsonApi/build-status/master) [![Code Coverage](https://scrutinizer-ci.com/g/mikemirten/JsonApi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mikemirten/JsonApi/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mikemirten/JsonApi/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mikemirten/JsonApi/?branch=master)

This repository contains PHP-implementation of the [JsonAPI standard](http://jsonapi.org/).

## How to install
Through composer:

```composer require mikemirten/json-api```

## How to use

The component contains a number of modules:
- Document
- Hydrator
- HTTP-Client

### Document
The document represents the Json API standard as a number of classes. The root of the document represented by four of them:

```Mikemirten\Component\JsonApi\Document\NoDataDocument```: Document contains no resources.

```Mikemirten\Component\JsonApi\Document\SingleResourceDocument```: Document contains single resource object.

```Mikemirten\Component\JsonApi\Document\ResourceCollectionDocument```: Document is a collection of resources. Can contain any amount of resources including 0.

```Mikemirten\Component\JsonApi\Document\AbstractDocument```: Base document for enlisted before. Cannot be instanciated, but can be used as an expected type when certain data structure is not known.

### Hydrator
The hydrator allows to create a structure of document by just decoded JSON.

### HTTP-Client
A simple HTTP-client compatible with PSR-7, handles bodies of requests and responses converting them to/from document.
