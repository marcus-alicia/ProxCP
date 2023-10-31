declare module "node-crc" {
    /**
     * bits = 64, reflection = true, exp = 0xD800000000000000, ini = 0xFFFFFFFFFFFFFFFF, fix = 0xFFFFFFFFFFFFFFFF
     */
    export function crc64iso(data: Buffer): Buffer | false;

    /**
     * bits = 64, reflection = true, exp = 0x95AC9329AC4BC9B5u64, ini = 0x0000000000000000, fix = 0x0000000000000000
     */
    export function crc64jones(data: Buffer): Buffer | false;

    /**
     * bits = 64, reflection = false, exp = 0x42F0E1EBA9EA3693, ini = 0xFFFFFFFFFFFFFFFF, fix = 0xFFFFFFFFFFFFFFFF
     */
    export function crc64we(data: Buffer): Buffer | false;

    /**
     * bits = 64, reflection = false, exp = 0x42F0E1EBA9EA3693, ini = 0x0000000000000000, fix = 0x0000000000000000
     */
    export function crc64ecma(data: Buffer): Buffer | false;

    /**
     * bits = 64, reflection = false, exp = 0x42F0E1EBA9EA3693, ini = 0x0000000000000000, fix = 0x0000000000000000
     */
    export function crc64(data: Buffer): Buffer | false;

    /**
     * bits = 32, reflection = true, exp = 0xEDB88320, ini = 0xFFFFFFFF, fix = 0xFFFFFFFF
     */
    export function crc32ieee(data: Buffer): Buffer | false;

    /**
     * bits = 32, reflection = true, exp = 0xEDB88320, ini = 0xFFFFFFFF, fix = 0xFFFFFFFF
     */
    export function crc32b(data: Buffer): Buffer | false;

    /**
     * bits = 32, reflection = true, exp = 0xEDB88320, ini = 0xFFFFFFFF, fix = 0xFFFFFFFF
     */
    export function crc32(data: Buffer): Buffer | false;

    /**
     * bits = 32, reflection = true, exp = 0x82F63B78, ini = 0xFFFFFFFF, fix = 0xFFFFFFFF
     */
    export function crc32c(data: Buffer): Buffer | false;

    /**
     * bits = 32, reflection = false, exp = 0x04C11DB7, ini = 0xFFFFFFFF, fix = 0xFFFFFFFF, reversed
     */
    export function crc32mhash(data: Buffer): Buffer | false;

    /**
     * bits = 16, reflection = true, exp = 0xA001, ini = 0x0000, fix = 0x0000
     */
    export function crc16ibm(data: Buffer): Buffer | false;

    /**
     * bits = 16, reflection = true, exp = 0xA001, ini = 0x0000, fix = 0x0000
     */
    export function crc16(data: Buffer): Buffer | false;

    /**
     * bits = 16, reflection = false, exp = 0x1021, ini = 0xFFFF, fix = 0x0000
     */
    export function crc16ccitt(data: Buffer): Buffer | false;

    /**
     * bits = 16, reflection = false, exp = 0x1021, ini = 0xFFFF, fix = 0x0000
     */
    export function crcccitt(data: Buffer): Buffer | false;

    /**
     * bits = 8, reflection = false, exp = 0x07, ini = 0x00, fix = 0x00
     */
    export function crc8atm(data: Buffer): Buffer | false;

    /**
     * bits = 8, reflection = false, exp = 0x07, ini = 0x00, fix = 0x00
     */
    export function crc8(data: Buffer): Buffer | false;

    /**
     * bits = 8, reflection = false, exp = 0x9B, ini = 0xFF, fix = 0x00
     */
    export function crc8cdma(data: Buffer): Buffer | false;

    /**
     * Compute a n-bit CRC value. You can change the length of bits, reflection, expression, the initial value, the final xor value by yourself.
     */
    export function crc(bits: number, reflection: boolean, expL: number, expH: number, iniL: number, iniH: number, fixL: number, fixH: number, data: Buffer): Buffer | false;
}
