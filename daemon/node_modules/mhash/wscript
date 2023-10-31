import Options
from os.path import abspath
import os

srcdir = '.'
blddir = 'build'
mhashdir = abspath(srcdir) + "/deps/mhash"
VERSION = '0.0.1'

def set_options(opt):
  opt.tool_options('compiler_cxx')

def configure(conf):
  conf.check_tool('compiler_cxx')
  conf.check_tool('node_addon')
  conf.env.set_variant("default")

  # configure mhash
  print "Configuring mhash library ..."
  cmd = "cd deps/mhash && sh configure --with-CPPFLAGS='-fPIC'"
  if os.system(cmd) != 0:
    conf.fatal("Configuring mhash failed.")

def build(bld):
  print "Building mhash library ..."
  cmd = "cd deps/mhash && make"
  if os.system(cmd) != 0:
    conf.fatal("Building mhash failed.")
  else:
    obj = bld.new_task_gen('cxx', 'shlib', 'node_addon')
    obj.target = 'mhash'
    obj.source = 'mhash.cc'
    obj.includes = [mhashdir + '/include']
    obj.cxxflags = ['-O2']
    obj.linkflags = [mhashdir + '/lib/.libs/libmhash.a']

def shutdown():
  if Options.commands['clean']: os.system("cd deps/mhash && make clean")
  if Options.commands['distclean']: os.system("cd deps/mhash && make distclean")
