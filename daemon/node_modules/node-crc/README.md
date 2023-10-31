CRC For Node.js
=================================

[![CI](https://github.com/magiclen/node-crc/actions/workflows/ci.yml/badge.svg)](https://github.com/magiclen/node-crc/actions/workflows/ci.yml)

## Usage

You can use `crc` function to compute a CRC value by providing the length of bits, expression, reflection, an initial value and a final xor value. For example, if you want to compute a CRC-24 value.

```javascript
var result = crc.crc(24, false, 0x00864cfb, 0x00000000, 0x00b704ce, 0x00000000, 0x00000000, 0x00000000, Buffer.from('hello', 'utf8')).toString('hex');
// Arguments: the length of bits, reflection, low bits of expression, high bits of expression, low bits of the initial value, high bits of the initial value, low bits of the final xor value, high bits of the final xor value, the source data buffer
```

To simplify the usage, there are several common versions of CRC whose computing functions are already built-in.

  * crc8(crc8atm)
  * crc8cdma
  * crc16(crc16ibm)
  * crc16ccitt(crcccitt)
  * crc32(crc32ieee, also called crc32b in `mhash`)
  * crc32mhash
    * `mhash` is a common library which has two weird versions of CRC32 called `crc32` and `crc32b`. `crc32` and `crc32mhash` in this module are `crc32b` and `crc32` in mhash respectively.
  * crc32c
  * crc64(crc64ecma)
  * crc64we
  * crc64iso
  * crc64jones

Input data and output data are buffers.

For instance,

```javascript
const result = crc.crc32(Buffer.from('hello', 'utf8')).toString('hex');
const result2 = crc.crc64(Buffer.from('world', 'utf8')).toString('hex');
```

## License

[MIT](LICENSE)
