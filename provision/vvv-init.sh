#!/usr/bin/env bash
# Provision WordPress Stable

DOMAIN=`get_primary_host "${VVV_SITE_NAME}".test`
DOMAINS=`get_hosts "${DOMAIN}"`

cd ${VVV_PATH_TO_SITE}/public_html


if [ -d .git ]; then
  git pull
else
  git clone https://github.com/potsky/PimpMyLog.git .
fi
if [ ! -f yaml.php ]; then
  cp ${VVV_PATH_TO_SITE}/provision/yaml.php ${VVV_PATH_TO_SITE}/public_html
fi

cd ${VVV_PATH_TO_SITE}/provision
if [ -f config.user.php ]; then
  rm ${VVV_PATH_TO_SITE}/public_html/config.user.php
fi
cp config.user.php ${VVV_PATH_TO_SITE}/public_html/

# Nginx Logs
mkdir -p ${VVV_PATH_TO_SITE}/log
touch ${VVV_PATH_TO_SITE}/log/error.log
touch ${VVV_PATH_TO_SITE}/log/access.log

# Enable the debugger
touch ${VVV_PATH_TO_SITE}/public_html/inc/test.PLEASE_REMOVE_ME.access_from_192.168.50.1_only.php


cp -f "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf.tmpl" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"
sed -i "s#{{DOMAINS_HERE}}#${DOMAINS}#" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"

if [ -n "$(type -t is_utility_installed)" ] && [ "$(type -t is_utility_installed)" = function ] && `is_utility_installed core tls-ca`; then
    sed -i "s#{{TLS_CERT}}#ssl_certificate /vagrant/certificates/${VVV_SITE_NAME}/dev.crt;#" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"
    sed -i "s#{{TLS_KEY}}#ssl_certificate_key /vagrant/certificates/${VVV_SITE_NAME}/dev.key;#" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"
else
    sed -i "s#{{TLS_CERT}}##" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"
    sed -i "s#{{TLS_KEY}}##" "${VVV_PATH_TO_SITE}/provision/vvv-nginx.conf"
fi
