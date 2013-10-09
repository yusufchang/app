/**
 * to be run from minified_assets.php, since that does the heavy lifting of
 * finding what files to run this on
 */
var sys = require('sys'),
	exec = require('child_process').exec,
	numComplete = 0;

var onComplete = function(error, stdOut, stdErr) {
	++numComplete;
	console.log("done");
}