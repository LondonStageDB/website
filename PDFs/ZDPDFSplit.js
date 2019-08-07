/*
This is used after ZeroDates.js and ZeroDatesFilenames.js have been run. Works the same as SplitPDF.js, but tracks each PDF and changes between them as needed. 
After this is done, run ImportToDB.js on the zero-dates-filenames.json file to update the database. 
*/

var fs = require('fs');
var scissors = require('scissors');

var datesArr = JSON.parse(require('fs').readFileSync('zero-dates-filenames.json', 'utf8'));

var volumes = ['1', '2-1', '2-2', '3-1', '3-2', '4-1', '4-2', '4-3', '5-1', '5-2', '5-3'];
var filenames = datesArr.map(obj => obj.filename);
var uniqueFilenames = datesArr.filter((item, index, self) =>
  index === self.findIndex((t) => (
    t.filename === item.filename
  ))
);

var vol = [];

vol['1'] = scissors('source/TheLondonStage1660-1800vol1.pdf');
vol['2-1'] = scissors('source/TheLondonStage1660-1800vol2.1.pdf');
vol['2-2'] = scissors('source/TheLondonStage1660-1800vol2.2.pdf');
vol['3-1'] = scissors('source/TheLondonStage1660-1800vol3.1.pdf');
vol['3-2'] = scissors('source/TheLondonStage1660-1800vol3.2.pdf');
vol['4-1'] = scissors('source/TheLondonStage1660-1800vol4.1.pdf');
vol['4-2'] = scissors('source/TheLondonStage1660-1800vol4.2.pdf');
vol['4-3'] = scissors('source/TheLondonStage1660-1800vol4.3.pdf');
vol['5-1'] = scissors('source/TheLondonStage1660-1800vol5.1.pdf');
vol['5-2'] = scissors('source/TheLondonStage1660-1800vol5.2.pdf');
vol['5-3'] = scissors('source/TheLondonStage1660-1800vol5.3.pdf');

function writePDF(volume, pages, destination) {
    const pgs = vol[volume].pages(pages);
    const output = fs.createWriteStream(destination);
    return new Promise((resolve, reject) => {

        output.on('error', reject);
        output.on('end', function() {
          output.end();
        });
        pgs.pdfStream().pipe(output);
    });
}

const promises = uniqueFilenames.map(evt => {
    const volume = evt.volume;
    const pages = evt.page;
    const destination = 'pdfs2/' + evt.filename;

    return writePDF(volume, pages, destination);
});

Promise.all(promises).then(_ => {
    console.log('We done');
}).catch(err => {
    console.error(err);
});
