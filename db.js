const mysql = require('mysql2');
const conn = mysql.createConnection({
  host: '127.0.0.1',
  user: 'root', //Sesuaikan
  password: '',  //Sesuaikan
  database: 'tom_sushi'
});

conn.connect(err => {
  if (err) throw err;
  console.log('Berhasil terhubung');
});

module.exports = conn;