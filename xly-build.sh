#!/bin/sh
PRODUCT_NAME="oms"
APP_NAME="orderui"
SVN_NAME="oms-orderui"
rm -rf output
mkdir -p output/app/$APP_NAME
mkdir -p output/conf/app/$APP_NAME
mkdir -p output/webroot/$APP_NAME
cp -r actions controllers library models script Bootstrap.php output/app/$APP_NAME
cp -r conf/*  output/conf/app
cp -r index.php  output/webroot/$APP_NAME
cd output
find ./ -name .svn -exec rm -rf {} \;
