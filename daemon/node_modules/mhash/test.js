"use strict";

const hash = require("./index"),
	path = require("path"),
	fs = require("fs");

console.log(hash("md2", "testing"));

console.log("\nNext should be:\n0c1e02ff");
console.log(hash("adler32", "testing"));

console.log("\nNext should be:\n431bb50c767c42ed2f050101b71575c632ce913e4324c700a9b75c02602f85aeb2dffbb367bcc52ead3624da112005ecced05f67dba3c470aec02697dce06df4");
console.log(hash("whirlpool", fs.readFileSync(path.join(__dirname, "hashtestdata.png"))));
