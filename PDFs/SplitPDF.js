/*
SplitPDF.js is used to split out each PDF into individual pages or sets of pages depending on what each date requires. 
The variables startYear, folder, datesArr, and pdfPath will need to be updated for each file. 
Meant to be run for each file in /pages. Choose the matching PDF (by date range) in /source for the pdfPath. 
*/

var fs = require('fs');
var scissors = require('scissors');

var startYear = '1792';
var folder = 'vol5-3/';
var datesArr = JSON.parse(require('fs').readFileSync('dates/working-dates_1792-1800.json', 'utf8'));
var pdfPath = 'source/TheLondonStage1660-1800vol5.3.pdf';
var pdf = scissors(pdfPath);

var allPages = [];
var allDates = [];

function onlyUnique(value, index, self) { 
  return self.indexOf(value) === index;
}

// Creates one array of date objects, not split up by year/month this time. But year and month are included as properties in each.
// The 'page' property is an array of pages the date is found on. And 'filename' property contains final folder/filename with all page numbers included. 
for (var y=startYear; y<datesArr.length; y++) {
  if(datesArr[y]) {
    for (var m=0; m<12; m++) {
      if(datesArr[y][m]) {
        let pages = [];
        // Only want unique dates - no multiples.
        const dates = datesArr[y][m].dates.filter(onlyUnique);

        dates.forEach((value) => {
          var existing = datesArr[y][m].objects.filter((v, i) => {
            return v.id === value;
          });
          if (existing.length) {
            const pages = existing.map(val => parseInt(val.page));
            filename = `${folder}${pages.toString().replace(/,/g, '-')}.pdf`;
            existing[0].page = pages;
            existing[0].filename = filename;
            allPages.push(filename);
            allDates.push(existing[0]);
          }
        });
      }
    }
  }
}

const uniqueDates = allDates.filter((item, index, self) =>
  index === self.findIndex((t) => (
    t.filename === item.filename
  ))
);
console.log(uniqueDates.length);

const firstHalf = uniqueDates.splice(800); // Eats memory, so split up into chunks. Run, edit this, and run again for each chunk.

function writePDF(pages, destination) {
    const pgs = pdf.pages(pages);
    const output = fs.createWriteStream(destination);
    return new Promise((resolve, reject) => {

        output.on('error', reject);
        output.on('end', function() {
          output.end();
        });
        pgs.pdfStream().pipe(output);
    });
}

// Splits out the PDF by filenames found in the filename-unique uniqueDates array
const promises = firstHalf.map(evt => {
    const pages = evt.page;
    const destination = 'pdfs/' + evt.filename;
    return writePDF(pages, destination);
});

Promise.all(promises).then(_ => {
    console.log('We done');
}).catch(err => {
    console.error(err);
});

fs.writeFile('pages/dates-final.json', JSON.stringify(allDates));
