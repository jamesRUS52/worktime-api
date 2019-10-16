<h2>API v1 start endpoint</h2>
<?=BASEURL;?>/api/v1/

<h2>API Documentaion</h2>
<a href="https://app.swaggerhub.com/apis-docs/jamesRUS52/worktime/1.0.0" target="blank">https://app.swaggerhub.com/apis-docs/jamesRUS52/worktime/1.0.0</a>

<h2>Main worktime functions</h2>
<a href="<?=BASEURL;?>/api/v1/calendar/list" target="blank">/calendar/list</a> - Get list of available calendars<br>

<a href="<?=BASEURL;?>/api/v1/calendar?calendar=RUS&startdate=2019-01-01&enddate=2019-10-31" target="blank">/calendar</a> - Get a calendar with work and non working periods<br>

<a href="<?=BASEURL;?>/api/v1/calendar/date?calendar=RUS" target="blank">/calendar/date</a> - Get current or certain date description<br>

<a href="<?=BASEURL;?>/api/v1/calendar/datetime?calendar=RUS" target="blank">/calendar/datetime</a> - Get current or certain datetime description<br>

<a href="<?=BASEURL;?>/api/v1/calendar/workdays?calendar=RUS&startdate=2019-10-14&enddate=2019-10-20" target="blank">/calendar/workdays</a> - Get count of working and nonworking days in given period<br>

<a href="<?=BASEURL;?>/api/v1/calendar/worktimes?calendar=RUS&startdatetime=2019-10-21 10:00:00&enddatetime=2019-10-21 11:00:00&unit=hour" target="blank">/calendar/worktimes</a> - Get an amount of working and non-working time in a period<br>

<a href="<?=BASEURL;?>/api/v1/calendar/neartime?calendar=RUS&unit=hour" target="blank">/calendar/neartime</a> - Get the next/prev work/non-work time, remaining and elapseded time from/to last/next change period<br>

<h2>SLA functions</h2>
<a href='<?=BASEURL;?>/api/v1/sla?startperiod=2019-10-01 00:00:00&endperiod=2019-11-01 00:00:00&calendar=RUS&unit=minute&nonserveperiods=[{"start":"2019-10-03 12:00:00","end":"2019-10-03 12:01:00"},{"start":"2019-10-06 22:00:00","end":"2019-10-06 23:00:00"}]' target="blank">/sla</a> - Get SLA for period excluding non-working time<br>


<h2>Free for use</h2>

<h2>Contacts</h2>
To send issue please use <a href="https://github.com/jamesRUS52/worktime" target="blank">github</a>