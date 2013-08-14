#!/usr/bin/env node
/**
 * Run:
 * $ sudo aptitude install libprotobuf-dev protobuf-compiler libmysqlclient-dev
 * $ npm install https://github.com/fuwaneko/node-protobuf/archive/master.tar.gz
 * $ npm install rethinkdb js-yaml mysql-libmysqlclient
 *
 * When running this script provide DB YAML config file using YAML env variable
 */
var LIMIT = process.env.LIMIT || 100000,
    BATCH_SIZE = process.env.BATCH || 100,
    CACHE_SIZE = process.env.CACHE_SIZE || 32, // in MB
    DURABILITY = process.env.DURABILITY  || 'hard'; // hard / soft

function getDBConfig(dbConfigFile) {
    var yaml = require('js-yaml'),
        fs = require('fs'),
        config = yaml.load(fs.readFileSync(dbConfigFile).toString());

    return config[0];
}

// connect to mysql
var dbConfig = getDBConfig(process.env.YAML),
    devConfig = dbConfig.serverTemplate,
    dbr = require('mysql-libmysqlclient').createConnectionSync(),
    result,
    row;

    dbr.connectSync(
        Object.keys(dbConfig.sectionLoads['c1']).pop(),
        devConfig.user,
        devConfig.password,
        'muppet'
    );

if (!dbr.connectedSync()) {
    console.log("Connection error " + conn.connectErrno + ": " + conn.connectError);
    process.exit(1);
}

console.log('Connected to mysql, fetching up to ' + LIMIT + ' rows...');

// get data from mysql
dbr.realQuerySync("SELECT page_id,page_title,page_namespace,cl_to,cl_type,cl_timestamp,cl_sortkey  FROM `page` INNER JOIN `categorylinks` ON ((cl_from = page_id))  WHERE page_namespace IN ('0','112') LIMIT " + LIMIT);
result = dbr.storeResultSync();

console.log('Preparing batches of ' + BATCH_SIZE + ' rows each...');

var batches = [],
	batch = [],
	i = 0,
        id = 1;

while (row = result.fetchRowSync()) {
  Object.keys(row).forEach(function(key) {
     if (row[key] instanceof Buffer) {
       row[key] = row[key].toString();
     }
  });
  
  //row.id = id++;

  batch.push(row);

  //  prepare batches
  i++;
  if (i >= BATCH_SIZE) {
     batches.push(batch);
     i = 0;
     batch = [];
  }
}

// push last batch
if (i > 0) {
  batches.push(batch);
}

console.log('JSON raw size: ' + (JSON.stringify(batches).length / 1024).toFixed(2) + ' kB');
console.log('No batches: ' + batches.length);

var globalTime = Date.now();

// connect to rethinkdb
var r = require('rethinkdb');
console.log('Protobuf implementation: ' + r.protobuf_implementation);

r.connect({ host: 'dbstore-s1', port: 28015 }, function(err, conn) {
    if(err) throw err;

    console.log('Connected');

//    r.db('test').tableCreate('macbre_categorylinks', {cache_size: CACHE_SIZE, hard_durability: DURABILITY === 'hard'}).run(conn, function(err, res) {
        if(err) throw err;

        console.log('Inserting data (' + BATCH_SIZE + ' rows per batch with ' + DURABILITY + ' durability)...');

        // add data
        function insertBatch() {
            var batch = batches.shift();

            // all items added
            if (typeof batch === 'undefined') {
		conn.close();
                console.log('Done in ' + (Date.now() - globalTime) + ' ms');
                process.exit(0);
            }

            var time = Date.now();

            r.table('macbre_categorylinks').insert(batch).run({connection: conn, noreply: DURABILITY === 'soft'}, function(err, res) {
                var t = (Date.now() - time);

                if(err) throw err;

                console.log(batch.length + ' rows inserted in ' + t + ' ms (' + (t / batch.length).toFixed(4)  +  ' ms per row)');
                insertBatch();
            })
        }

        insertBatch();
//    });
});
