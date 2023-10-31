MagicCrypt
=================================

[![CI](https://github.com/magiclen/node-magiccrypt/actions/workflows/ci.yml/badge.svg)](https://github.com/magiclen/node-magiccrypt/actions/workflows/ci.yml)

## For Node.js

### Encrypt

You can use **encrypt** method to encrypt any string. For example,

```javascript
const mc = new MagicCrypt('magickey', 256);
console.log(mc.encrypt('http://magiclen.org'));
```

The result is,

    DS/2U8royDnJDiNY2ps3f6ZoTbpZo8ZtUGYLGEjwLDQ=

To encrypt any data buffer to a base64 string,

```javascript
const mc = new MagicCrypt('magickey', 256);
console.log(mc.encryptData(buffer));
```

### Decrypt

You can use **decrypt** method to decrypt any encrypted string. For example,

```javascript
const mc = new MagicCrypt('magickey', 256);
console.log(mc.decrypt('DS/2U8royDnJDiNY2ps3f6ZoTbpZo8ZtUGYLGEjwLDQ='));
```

The result is,

    http://magiclen.org

To decrypt any base64 string to data buffer,

```javascript
const mc = new MagicCrypt('magickey', 256);
const buffer = mc.decryptData(base64);
```

## For Java

Refer to [https://github.com/magiclen/MagicCrypt](https://github.com/magiclen/MagicCrypt).

## For PHP

Refer to [https://github.com/magiclen/MagicCrypt](https://github.com/magiclen/MagicCrypt).

## For Rust

Refer to [https://github.com/magiclen/rust-magiccrypt](https://github.com/magiclen/rust-magiccrypt).

## License

[MIT](LICENSE)
