{
	"targets":
	[
		{
			"target_name": "mhash",
			"sources": [ "mhash.cc" ],
			"include_dirs" : [ "deps/mhash/include", "<!(node -e \"require('nan')\")"],
			"dependencies" : [ "libmhash" ],
			"libraries" : [ "<(module_root_dir)/deps/mhash/lib/.libs/libmhash.a" ]
		},
		{
			"target_name" : "libmhash",
			"type" : "none",
			"actions" :
			[
				{
					"action_name" : "test",
					 # a hack to run deps/mhash ./configure during `node-gyp configure`
					'inputs': ['<!@(sh libmhash-config.sh)'],
					'outputs': [''],
					'action': [
					# run deps/mhash `make`
					'sh', 'libmhash-build.sh'
					]
				}
			]
		}
	]
}