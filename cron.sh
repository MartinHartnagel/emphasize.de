#!/bin/sh
dir=`dirname $0`
process=$1
domain=`cat $dir/includes/config.php|grep '$domain = '|sed "s/.*\"http/http/g"|sed "s/\";.*//g"`
# extract host, port and path
host=`echo $domain|sed "s/.*\/\///g"|sed "s/\(\.[^\/\"]*\).*/\1/g"`
path=`echo $domain|sed "s/.*$host//g"|cut -c2-9999`
port=`echo $domain|sed "s/.*://g"|sed "s/\/.*//g"`
if [ "$port" = "" ]
then 
  if [ `echo $domain|grep "https://"`= ""]
  then
    port=80
  else
    port=443
  fi
fi
# tcp request 
exec 3<>/dev/tcp/$host/$port
echo -e "GET /$path/util/process$process.php HTTP/1.1\nhost: $host\n\n" >&3
cat <&3 >>$dir/cache/cron.txt
