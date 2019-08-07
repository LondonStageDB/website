/*
After running ZeroDates.js, this script is used to generate the filename for each date object. Then, run ZDPDFSplit.js to split out the PDF. 
*/

var fs = require('fs');

var datesArr = JSON.parse(require('fs').readFileSync('zero-dates.json', 'utf8'));

datesArr.forEach(function(item) {
  folder = `vol${item.volume}/`;
  filename = `${folder}${item.page.toString().replace(/,/g, '-')}.pdf`;
  item.filename = filename;
});

fs.writeFile('zero-dates1.json', JSON.stringify(datesArr));
