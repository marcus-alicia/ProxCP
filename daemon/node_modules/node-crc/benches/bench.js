const { describe, it } = require("mocha");
const mlog = require("mocha-logger");
const crc = require("crc");

const _crc = require("..");

let a = "";
for (let i = 0;i < 10000000;++i) {
    a += "COO";
}
let buffer = Buffer.from(a, "utf8");

describe("CRC-8", function () {
    let startTime;
    let endTime;

    it("another crc module", function () {
        let result;
        startTime = Date.now();
        result = crc.crc8(buffer).toString(16);
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });

    it("this module", function () {
        let result;
        startTime = Date.now();
        result = _crc.crc8(buffer).toString("hex");
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });
});

describe("CRC-16", function () {
    let startTime;
    let endTime;

    it("another crc module", function () {
        let result;
        startTime = Date.now();
        result = crc.crc16(buffer).toString(16);
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });

    it("this module", function () {
        let result;
        startTime = Date.now();
        result = _crc.crc16(buffer).toString("hex");
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });
});

describe("CRC-32", function () {
    let startTime;let endTime;

    it("another crc module", function () {
        let result;
        startTime = Date.now();
        result = crc.crc32(buffer).toString(16);
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });

    it("this module", function () {
        let result;
        startTime = Date.now();
        result = _crc.crc32(buffer).toString("hex");
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });
});

describe("CRC-64", function () {
    let startTime;
    let endTime;

    it("another crc module", function () {
        mlog.log("I cannot compute CRC-64!");
    });

    it("this module", function () {
        let result;
        startTime = Date.now();
        result = _crc.crc64(buffer).toString("hex");
        endTime = Date.now();
        mlog.log(endTime - startTime, "milliseconds");
        mlog.log("Result HEX:", result);
    });
});
