# Worktime
API for worktime and sla functions

## API Documentaion
https://app.swaggerhub.com/apis-docs/jamesRUS52/worktime/1.0.0

### Main worktime functions
/calendar/list - Get list of available calendars

/calendar - Get a calendar with work and non working periods

/calendar/date - Get current or certain date description

/calendar/datetime - Get current or certain datetime description

/calendar/workdays - Get count of working and nonworking days in given period

/calendar/worktimes - Get an amount of working and non-working time in a period

/calendar/neartime - Get the next/prev work/non-work time, remaining and elapseded time from/to last/next change period


### SLA functions
/sla - Get SLA for period excluding non-working time


## Licence 
Apache 2.0