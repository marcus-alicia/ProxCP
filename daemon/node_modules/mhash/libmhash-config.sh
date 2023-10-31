cd deps/mhash
make clean distclean >node_libmhash_configure.out 2>&1
sh configure --with-CPPFLAGS='-fPIC' >>node_libmhash_configure.out 2>&1
cd ../..