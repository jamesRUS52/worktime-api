#!/bin/bash

header_config='-H x-config: { "timezone": "Europe/Moscow", "dateformat": "Y-m-d", "datetimeformat": "Y-m-d h:i:s", "worktime": [ { "short": false, "start": "09:00:00", "end": "18:00:00" }, { "short": true, "start": "09:00:00", "end": "17:00:00" } ] }"'

for url in 'calendar/list' '/calendar?calendar=RUS&startdate=2019-01-10&enddate=2019-12-31' '/calendar/date?calendar=RUS' '/calendar/datetime?calendar=RUS' '/calendar/workdays?calendar=RUS&startdate=2019-01-10&enddate=2019-12-31' '/calendar/worktimes?calendar=RUS&startdatetime=2019-01-10%2000%3A00%3A00&enddatetime=2019-12-31%2023%3A59%3A59&unit=hour' '/calendar/neartime?calendar=RUS&&unit=hour' '/sla?calendar=RUS&startperiod=2019-01-01%2000%3A00%3A00&endperiod=2019-12-31%2023%3A59%3A59&unit=hour&nonserveperiods=%5B%7B"start"%3A"2019-02-01%2001%3A00%3A00"%2C"end"%3A"2019-02-01%2002%3A00%3A00"%7D%5D'
do
  #echo $url
  status=`curl -s -w statuscode:%{http_code} $header_config https://worktime-test.james52.ru/api/v1/$url | tail -1|sed 's/^.*statuscode\://'`
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