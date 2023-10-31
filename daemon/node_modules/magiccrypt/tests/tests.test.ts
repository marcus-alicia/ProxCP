import { describe, it } from "mocha";
import { expect } from "chai";

import MagicCrypt from "../src/lib";

describe("Encrypt", function () {
    it("should encrypt a string by AES-256", function () {
        const mc = new MagicCrypt("magickey", 256);
        const result = mc.encrypt("http://magiclen.org");
        expect(result).to.equal("DS/2U8royDnJDiNY2ps3f6ZoTbpZo8ZtUGYLGEjwLDQ=");
    });

    it("should encrypt a string by AES-192", function () {
        const mc = new MagicCrypt("magickey", 192);
        const result = mc.encrypt("http://magiclen.org");
        expect(result).to.equal("p0X9IHMqaxA78T0X8Y9DnNeEmVXIgUxrXmeyUEO1Muo=");
    });

    it("should encrypt a string by AES-128", function () {
        const mc = new MagicCrypt("magickey", 128);
        const result = mc.encrypt("http://magiclen.org");
        expect(result).to.equal("Pdpj9HqTAN7vY7Z9msMzlIXJcNQ5N+cIJsiQhLqyjVI=");
    });

    it("should encrypt a string by DES-64", function () {
        const mc = new MagicCrypt("magickey", 64);
        const result = mc.encrypt("http://magiclen.org");
        expect(result).to.equal("nqIQCAbQ0TKs6x6eGRdwrouES803NhvC");
    });

    it("should encrypt a buffer by AES-256", function () {
        const mc = new MagicCrypt("magickey", 256);
        const result = mc.encryptData(Buffer.from("http://magiclen.org", "utf8"));
        expect(result).to.equal("DS/2U8royDnJDiNY2ps3f6ZoTbpZo8ZtUGYLGEjwLDQ=");
    });
});

describe("Decrypt", function () {
    it("should decrypt a string by AES-256", function () {
        const mc = new MagicCrypt("magickey", 256);
        const result = mc.decrypt("DS/2U8royDnJDiNY2ps3f6ZoTbpZo8ZtUGYLGEjwLDQ=");
        expect(result).to.equal("http://magiclen.org");
    });

    it("should decrypt a string by AES-192", function () {
        const mc = new MagicCrypt("magickey", 192);
        const result = mc.decrypt("p0X9IHMqaxA78T0X8Y9DnNeEmVXIgUxrXmeyUEO1Muo=");
        expect(result).to.equal("http://magiclen.org");
    });

    it("should decrypt a string by AES-128", function () {
        const mc = new MagicCrypt("magickey", 128);
        const result = mc.decrypt("Pdpj9HqTAN7vY7Z9msMzlIXJcNQ5N+cIJsiQhLqyjVI=");
        expect(result).to.equal("http://magiclen.org");
    });

    it("should decrypt a string by DES-64", function () {
        const mc = new MagicCrypt("magickey", 64);
        const result = mc.decrypt("nqIQCAbQ0TKs6x6eGRdwrouES803NhvC");
        expect(result).to.equal("http://magiclen.org");
    });

    it("should decrypt a buffer by AES-256", function () {
        const mc = new MagicCrypt("magickey", 256);
        const result = mc.decryptData("DS/2U8royDnJDiNY2ps3f6ZoTbpZo8ZtUGYLGEjwLDQ=");
        expect(result.toString("utf8")).to.equal("http://magiclen.org");
    });
});
