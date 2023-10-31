Description
===========

Provides several hashing algorithms for [node.js](http://nodejs.org/) by binding to [mhash](http://mhash.sourceforge.net/)

NOTE: MacOS X users need to have XCode installed (provides gcc)


Installation with NPM
=====================

```bash
npm install mhash --python=python2
```

May need to manually run first to build mhash dep:
```
./libmhash-config.sh
./libmhash-build.sh
```

Usage
=====

```javascript
Syntax: hash("algo-type", String || Buffer)
//First argument is a string, one of the supported algorihms below.
//Second argument can either be a String or a Buffer.
```

```javascript
var hash = require("mhash");

console.log(hash("md2", "testing"));
```


Supported Hashing Algorithms
============================
* crc32

* crc32b

* md2

* md4

* md5

* haval128

* haval160

* haval192

* haval224

* haval256

* sha1

* sha224

* sha256

* sha384

* sha512

* ripemd128

* ripemd160

* ripemd256

* ripemd320

* tiger128

* tiger160

* tiger192

* gost

* whirlpool

* adler32

* snefru128

* snefru256


Known Issues
============

The crc32 and crc32b may not be what you expect due the way the mhash library computes these.

See [Issue #1](https://github.com/Sembiance/node-mhash/issues/1) for more details.


Manual Installation
===================

```bash
git clone "https://github.com/Sembiance/mhash.git"
cd mhash
node-gyp configure build
```
