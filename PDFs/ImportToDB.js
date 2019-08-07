/*
ImportToDB.js is used to import the filename into the database. You'll need to first create the 'BookPDF' column in your Events table. 
*/

var mysql = require('mysql');

var con = mysql.createConnection({
  host: "localhost",
  user: "your_user",
  password: "your_password",
  database: "London"
});

// Replace filename with each file inside /pages/ folder and rerun script
var datesArr = JSON.parse(require('fs').readFileSync('pages/zero-dates-filenames.json', 'utf8'));

con.connect(function(err) {
  if (err) throw err;
});

datesArr.forEach(function(el) {
  con.query("UPDATE Events SET BookPDF = ? WHERE EventDate = ?", [el.filename, el.date.replace(/-/g, '')], function (err, result, fields) {
    if (err) throw err;
  });
});

con.end();
