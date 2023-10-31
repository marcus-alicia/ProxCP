'use strict';

//var request;
var promisify = require('util').promisify;
var os = require('os');
var fs = require('fs');
var writeFile = promisify(fs.writeFile);
var readFile = promisify(fs.readFile);
var unlink = promisify(fs.unlink);
var mkdirp = promisify(require('@root/mkdirp'));
var path = require('path');

var defaults = {
	webroot: path.join(require('os').tmpdir(), 'acme-challenge')
};

module.exports.create = function(config) {
	var webroot = config.webroot || config.webrootPath || defaults.webroot;

	function tpl(str, ch) {
		return str
			.replace(/\s*{+\s*domain\s*}+\s*/gi, ch.identifier.value)
			.replace(/^~/, os.homedir());
	}

	return {
		// exposed to make testable
		_tpl: tpl,

		init: function(opts) {
			//request = opts.request;
			return null;
		},

		set: function(data) {
			// console.log('Add Key Auth URL', data);

			var ch = data.challenge;
			var pathname = tpl(webroot, ch);

			return mkdirp(pathname)
				.then(function() {
					return writeFile(
						path.join(pathname, ch.token),
						ch.keyAuthorization
					);
				})
				.then(function() {
					return null;
				});
		},

		get: function(data) {
			// console.log('List Key Auth URL', data);

			var ch = data.challenge;
			var pathname = tpl(webroot, ch);

			return readFile(path.join(pathname, ch.token), 'utf8')
				.then(function(keyAuth) {
					return { keyAuthorization: keyAuth };
				})
				.catch(function(err) {
					if ('ENOENT' !== err.code) {
						throw err;
					}
					return null;
				});
		},

		remove: function(data) {
			// console.log('Remove Key Auth URL', data);

			var ch = data.challenge;
			var pathname = tpl(webroot, ch);

			return unlink(path.join(pathname, ch.token)).then(function() {
				return null;
			});
		}
	};
};
