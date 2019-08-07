/*
There were several events that were missing PDF info, often due to being a zero-date (a month without a date or a year without a month/date).
A quick SQL script was used to find all of these in the database and output them to a zero-dates.json file. 
This script is simply used to add a page array to each object in the JSON file.
*/

var fs = require('fs');

var datesArr = JSON.parse(require('fs').readFileSync('zero-dates.json', 'utf8'));

datesArr.forEach(function(item) {
  item.page = [];
});

fs.writeFile('zero-dates1.json', JSON.stringify(datesArr));
