#!/bin/bash
mv ../app/vendors vendors_de_app
phpdoc -pp on -o HTML:frames:earthli -s on -d app/ -t ./source 
mv vendors_de_app ../app/vendors
