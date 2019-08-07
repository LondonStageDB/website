/*
PDF2JSON.js is used to read a given PDF (entered in the 'pdfPath' variable below) and output a JSON file of dates found split up by year and then by month. 
The Variables pdfPath, startYear, endYear, and lastYear will need to be updated for each PDF. 
*/

var fs = require('fs');

// Run `gulp dist-install` to generate 'pdfjs-dist' npm package files.
var pdfjsLib = require('pdfjs-dist');

// Set to first and last years in the PDF
var startYear = '1792';
var endYear = '1800';
var lastYear = '1792';

var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
// Set to first month covered in book
var lastMonth = 0; // Keep track of the last used month in case the OCR is bad and the page doesn't have one
var count = 0;

let allDatesArr = [];

// Loading file from file system into typed array
var pdfPath = process.argv[2] || 'source/TheLondonStage1660-1800vol5.3.pdf';

// If array has two months in it, send back first index of second month; else null
function hasTwoMonths(arr) {
  const length = arr.length;
  let index = null;
  for (var i = 0; i < length - 1; i++) {
    if (parseInt(arr[i].date) > parseInt(arr[i + 1].date)) {
      index = i + 1;
      break;
    }
  }
  return index;
}

// Create allDatesArr to be output to JSON later. Each date object includes the id (day of month), dayOfWk, month, year, full date, and page it is found on.
function toDate(arr, month, year, page) {
  let tempArr = [];
  if (!allDatesArr[year]) allDatesArr[year] = [];
  if (!allDatesArr[year][month]) allDatesArr[year][month] = [];
  arr.forEach((item, index) => {
    tempArr.push({ id: item.date, dayOfWk: item.dayofwk, 'month': month, 'year': year, date: new Date(year, month, item.date).toISOString().slice(0,10), page: page });
    allDatesArr[year][month].push({ id: item.date, dayOfWk: item.dayofwk, 'month': month, 'year': year, date: new Date(year, month, item.date).toISOString().slice(0,10), page: page });
  });
}

// Outputs whether or not a given month is sorted. Out of order is either a sign that a month array 
// includes more than one month, or the OCR was misread. Value gets output in final JSON file so it's easy to spot. 
function isSorted(arr) {
  for (let i = 0; i < arr.length - 1; i++) {
    if (parseInt(arr[i]) > parseInt(arr[i+1])) {
      return false;
    }
  }
  return true;
}

// Will be using promises to load document, pages and misc data instead of
// callback.
pdfjsLib.getDocument(pdfPath).then(function (doc) {
  var numPages = doc.numPages;
  console.log('# Document Loaded');
  console.log('Number of Pages: ' + numPages);
  console.log();

  var lastPromise; // will be used to chain promises
  lastPromise = doc.getMetadata().then(function (data) {
    console.log('# Metadata Is Loaded');
    console.log('## Info');
    console.log(JSON.stringify(data.info, null, 2));
    console.log();
    if (data.metadata) {
      console.log('## Metadata');
      console.log(JSON.stringify(data.metadata.getAll(), null, 2));
      console.log();
    }
  });

  var loadPage = function (pageNum) {
    return doc.getPage(pageNum).then(function (page) {
      return page.getTextContent().then(function (content) {
        // Content contains lots of information about the text layout and
        // styles, but we need only strings at the moment
        var strings = content.items.map(function (item) {
          return item.str;
        });

        // TODO
        // 1. Check if YEAR in first couple lines
        // 2. If so, check for Month name in first couple lines
        // 3. If so, check for date and/or perf titles

        var text = strings.join(' ');
        // Used to check for month/year at the top of a page.
        var monthRegEx = new RegExp(/(January|February|March|April|May|June|July|August|September|October|November|December),?\s+\d{4}/);
       
        /*
        // Check for a date in the text. Date usually includes a day of the week and a number.
        // Let's also include common OCR misreadings for day names and numbers.
        // t = 1
        // g = 9 (or 8)
        // II = 11
        // to = 10
        */
        var dateRegEx = new RegExp(/(Monday|Tuesday|Tut1day|W>?ednesday|Thursday|Friday|Saturday|Sunday)\s*(\d{1,2}|t|t\d|[0-3]t|g|[1-2]g|II|to)\s/g);

        console.log('# Page ' + pageNum);

        var monthMatch = monthRegEx.exec(text);
        var curMonth = null;
        var curYear = null;

        // Is there a month/year in the text? If so, set our curMonth/curYear equal to them. 
        if (monthMatch) {
          curMonth = monthMatch[0].match(/(January|February|March|April|May|June|July|August|September|October|November|December)/)[0];
          curYear = monthMatch[0].match(/[0-9]+/)[0];
          console.log(curMonth);
          console.log(curYear);
          console.log();
        }

        var datesMatch = dateRegEx.exec(text);
        var datesArr = [];
        while (datesMatch !== null) {
          count++;
          datesArr.push( {
            dayofwk: datesMatch[0].match(/(Monday|Tuesday|Tut1day|W>?ednesday|Thursday|Friday|Saturday|Sunday)/)[0],
            // Fix common date errors in OCR
            date: datesMatch[0].replace(/(Monday|Tuesday|Tut1day|W>?ednesday|Thursday|Friday|Saturday|Sunday)\s*/g, '').replace('to', '10').replace('t', '1').replace('g', '8').replace(/I/g, '1').replace('77', '11').match(/\d{1,2}/)[0],
          } );
          datesMatch = dateRegEx.exec(text);
        }

        let nextIndex = null;
        let nextDatesArr = [];
        // If page contains two months of info (if dates out of order), split out second month
        if (datesArr.length > 0 ) {
          nextIndex = hasTwoMonths(datesArr);
        }
        if (nextIndex !== null) {
          nextDatesArr = datesArr.splice(nextIndex, datesArr.length);
        }

        function getLastYear(lastMth, curYr, lastYr, nxtArrLngth) {
          if (nxtArrLngth > 0 && lastMth === 11) return lastYr;
          return curYr ? curYr : lastYr;
        }
        function getLastMonth(lastMth, curMth, nxtArrLngth) {
          if (nxtArrLngth > 0 || !curMth) return lastMth;
          return months.indexOf(curMonth);
        }

        // So we know where we are and can spot any issues
        if (datesArr.length > 0) {
          console.log(toDate(datesArr, getLastMonth(lastMonth, curMonth, nextDatesArr.length), getLastYear(lastMonth, curYear, lastYear, nextDatesArr.length), pageNum));
        }
        if (nextDatesArr.length > 0) {
          console.log();
          console.log(toDate(nextDatesArr, curMonth ? months.indexOf(curMonth) : lastMonth, curYear ? curYear : lastYear, pageNum));
        }

        console.log();
        console.log();

        lastMonth = curMonth ? months.indexOf(curMonth) : lastMonth;
        lastYear = curYear ? curYear : lastYear;
      }).then(function () {
      });
    })
  };

  // Loading of the first page will wait on metadata and subsequent loadings
  // will wait on the previous pages.
  for (var i = 1; i <= numPages; i++) {
    lastPromise = lastPromise.then(loadPage.bind(null, i));
  }
  return lastPromise;
}).then(function () {
  console.log('# End of Document');
  console.log(count);

  for (var y=startYear; y<=endYear; y++) {
    for (var m=0; m<12; m++) {
      if (allDatesArr[y] && allDatesArr[y][m]) {
        const dates = allDatesArr[y][m].map(val => val.id); // List of days of the month found. For easy tracking.
        const objects = allDatesArr[y][m]; // List of date objects found
        allDatesArr[y][m] = {};
        allDatesArr[y][m]['objects'] = objects;
        allDatesArr[y][m]['dates'] = dates;
        if (!isSorted(dates)) allDatesArr[y][m]['sorted'] = false;
      }
    }
  }
  fs.writeFile('dates/dates.json', JSON.stringify(allDatesArr));
}, function (err) {
    console.error('Error: ' + err);
});

