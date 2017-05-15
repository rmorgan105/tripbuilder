#!/usr/bin/env sh

cd /src/
echo ${APP_DIR} : ${DOCS_DIR} : ${FILE_FILTER}
rm -rf ${DOCS_DIR} && mkdir -p ${DOCS_DIR}
apidoc -i ${APP_DIR} -o ${DOCS_DIR} -f ${FILE_FILTER}
