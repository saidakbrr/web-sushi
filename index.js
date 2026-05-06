const express = require('express');
const bodyParser = require('body-parser');
const app = express();
const usersRoutes = require('./routes/users');
const pesananRoutes = require('./routes/pesanan');
const pelangganRoutes = require('./routes/pelanggan');
const menuRoutes = require('./routes/menu');
const detailPesananRoutes = require('./routes/detailpesanan');

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use('/api/users', usersRoutes);
app.use('/api/pesanan', pesananRoutes);
app.use('/api/pelanggan', pelangganRoutes);
app.use('/api/menu', menuRoutes);
app.use('/api/detailpesanan', detailPesananRoutes);

app.listen(3000, () => {
  console.log('Server running at http://localhost:3000');
});