const expect = require("chai").expect;
const { describe, it } = require("mocha");

const crc = require("..");

describe("CRC-8 Family", function () {
    it("should calculate CRC-8(CRC-8-ATM)", function () {
        const result = crc.crc8(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("92");
    });
    it("should calculate CRC-8-CDMA", function () {
        var result = crc.crc8cdma(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("4c");
    });
});

describe("CRC-16 Family", function () {
    it("should calculate CRC-16(CRC-16-IBM)", function () {
        const result = crc.crc16(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("34d2");
    });
    it("should calculate CRC-16-CCITT(CRC-CCITT)", function () {
        var result = crc.crcccitt(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("d26e");
    });
});

describe("CRC-32 Family", function () {
    it("should calculate CRC-32(CRC-32-IEEE) which is also called crc32b in mhash library", function () {
        const result = crc.crc32(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("3610a686");
    });
    it("should calculate crc32 according to the mhash library", function () {
        const result = crc.crc32mhash(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("3d653119");
    });
    it("should calculate CRC-32-C", function () {
        const result = crc.crc32c(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("9a71bb4c");
    });
});

describe("CRC-64 Family", function () {
    it("should calculate CRC-64(CRC-64-ECMA)", function () {
        const result = crc.crc64(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("40544a306137b6ec");
    });
    it("should calculate CRC-64-ISO", function () {
        const result = crc.crc64iso(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("3c3eeee2d8100000");
    });
    it("should calculate CRC-64-WE", function () {
        const result = crc.crc64we(Buffer.from("hello", "utf8")).toString("hex");
        expect(result).to.equal("ec5388479a7c913f");
    });
    it("should calculate CRC-64-JONES", function () {
        const result = crc.crc64jones(Buffer.from("123456789", "utf8")).toString("hex");
        expect(result).to.equal("e9c6d914c4b8d9ca");
    });
});

describe("CRC-24 Family", function () {
    it("should calculate CRC-24", function () {
        const result = crc.crc(
            24, false, 0x00864cfb, 0x00000000, 0x00b704ce, 0x00000000, 0x00000000, 0x00000000, Buffer.from("hello", "utf8"),
        ).toString("hex");
        expect(result).to.equal("47f58a");
    });
});
