#!/bin/bash

for url in 'calendar/list'
do
  echo $url
  status=`curl -s -w statuscode:%{http_code} https://worktime-test.james52.ru/api/v1/$url | tail -1|sed 's/^.*statuscode\://'`
  if [[ "$status" -eq "200" ]]
  then
    echo -n "."
  else
    echo "F"
    echo "Test fialed for $url with status code $status"
    exit 1
  fi
done
exit 0

