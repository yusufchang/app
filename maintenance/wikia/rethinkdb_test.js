#!/usr/bin/env node
/**
 * Run:
 * $ sudo aptitude install libprotobuf-dev protobuf-compiler libmysqlclient-dev
 * $ npm install -g node-gyp
 * $ npm install rethinkdb js-yaml mysql-libmysqlclient
 *
 * When running this script provide DB YAML config file using YAML env variable
 */
var BATCH_SIZE = process.env.BATCH || 100,
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

console.log('Connected to mysql, fetching rows...');

// get data from mysql
dbr.realQuerySync("SELECT page_id,page_title,page_namespace,cl_to,cl_type,cl_timestamp,cl_sortkey  FROM `page` INNER JOIN `categorylinks` ON ((cl_from = page_id))  WHERE page_namespace IN ('0','112')   LIMIT 100000");
result = dbr.storeResultSync();

var data = [];
while (row = result.fetchRowSync()) {
    data.push(row);
}

console.log('Rows from mysql: ' + data.length);

// connect to rethinkdb
var r = require('rethinkdb');
console.log('Protobuf implementation: ' + r.protobuf_implementation);

r.connect({ host: 'dbstore-s1', port: 28015 }, function(err, conn) {
    if(err) throw err;

    console.log('Connected');

    r.db('test').tableCreate('macbre_categorylinks', {hardDurability: DURABILITY === 'hard'}).run(conn, function(err, res) {
        if(err) throw err;

        console.log('Table created, insterting data (' + BATCH_SIZE + ' rows per batch with ' + DURABILITY + ' durability)...');

        // add data
        function insertBatch() {
            var batch = [],
                item,
                i = 0;

            while ((i < BATCH_SIZE) && (item = data.shift())) {
                batch.push(item);
                i++;
            }

            // all items added
            if (i === 0) {
                console.log('Done!');
                process.exit(0);
            }

            var time = Date.now();

            r.table('macbre_categorylinks').insert(batch, {durability: DURABILITY}).run(conn, function(err, res) {
                var t = (Date.now() - time);

                if(err) throw err;

                console.log(batch.length + ' rows inserted in ' + t + ' ms (' + Math.round(t / batch.length, 4)  +  ' ms per row)');
                insertBatch();
            })
        }

        insertBatch();
    });
});
