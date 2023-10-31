#!/usr/bin/env node
'use strict';

// See https://git.coolaj86.com/coolaj86/acme-challenge-test.js
var tester = require('acme-challenge-test');
require('dotenv').config();

// Usage: node ./test.js example.com username xxxxxxxxx
var record = process.argv[2] || process.env.RECORD;
var challenger = require('./index.js').create({
	webroot:
		'/tmp/acme-tests/{domain}/.well-known/acme-challenges/' ||
		process.env.WEBROOT
});

// The dry-run tests can pass on, literally, 'example.com'
// but the integration tests require that you have control over the domain
tester
	.testRecord('http-01', record, challenger)
	.then(function() {
		console.info('PASS', record);
	})
	.catch(function(e) {
		console.error(e.message);
		console.error(e.stack);
	});

var ch = { identifier: { value: 'foo.example.co.uk' } };
//var ch = { domain: 'foo.example.co.uk' };
var homeish = challenger._tpl('~/.local/tmp/acme-challenge', ch);
console.log(homeish);
if ('/' !== homeish[0] || /~/.test(homeish)) {
	throw new Error('Not the expected value for home tmp: ' + homeish);
}

var srvish = challenger._tpl('/srv/{domain}/.well-known/acme-challenge', ch);
console.log(srvish);
if ('/' !== srvish[0] || /~/.test(srvish)) {
	throw new Error('Not the expected value for srv template: ' + srvish);
}
