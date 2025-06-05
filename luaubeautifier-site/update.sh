mv code/luau_build luau_build

rm luau-beautifier
rm -rf code

git clone https://github.com/techhog8984/luau_beautifier.git code
cd code

mv ../luau_build .
make
mv luau-beautifier ..

rm -rf code

echo "DONE"