#!/usr/bin/env node
/**
 * Run:
 * $ sudo aptitude install libprotobuf-dev
 * $ npm install rethinkdb
 */
var r = require('rethinkdb');

console.log('Protobuf implementation: ' + r.protobuf_implementation);

r.connect({ host: 'dev-moli', port: 28015 }, function(err, conn) {
    if(err) throw err;

    console.log('Connected');
    console.log(conn);
});
