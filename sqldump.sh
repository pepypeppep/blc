#!/bin/bash

export PATH=/bin:/usr/bin:/usr/local/bin
TODAY=`date +"%Y%m%d-%H%M%S"`
################################################################
################## Update below values  ########################

DB_BACKUP_PATH='./tmp/db'
MYSQL_HOST='127.0.0.1'
MYSQL_PORT='30001'
MYSQL_USER='lms_dev'
MYSQL_PASSWORD='lms_dev'
DATABASE_NAME='lms_dev'

FILE_NAME="${DATABASE_NAME}-${TODAY}.sql.gz"

#################################################################

mkdir -p ${DB_BACKUP_PATH}
echo "Backup started for database - ${FILE_NAME}"


mysqldump --single-transaction \
   -h ${MYSQL_HOST} \
   -P ${MYSQL_PORT} \
   -u ${MYSQL_USER} \
   -p${MYSQL_PASSWORD} \
   ${DATABASE_NAME} | gzip > ${DB_BACKUP_PATH}/${FILE_NAME}

if [ $? -eq 0 ]; then
  echo "Database backup successfully completed ${DB_BACKUP_PATH}/${FILE_NAME}"
else
  echo "Error found during backup"
  exit 1
fi

