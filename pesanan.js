// routes/pesanan.js
const express = require("express");
const router = express.Router();
const db = require("../db");

// GET semua pesanan
router.get("/", (req, res) => {
  const sql = `
  SELECT 
  p.id,
  p.id_pelanggan,
  p.tanggal,
  p.total,
  p.status,
  pl.nama,
  pl.no_meja
  FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan = pl.id
    ORDER BY p.tanggal DESC

  `;
  db.query(sql, (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(results);
  });
});


// GET pesanan by ID
router.get("/:id", (req, res) => {
  const sql = `SELECT * FROM pesanan WHERE id = ?`;
  db.query(sql, [req.params.id], (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    if (results.length === 0) return res.status(404).json({ message: "Pesanan tidak ditemukan" });
    res.json(results[0]);
  });
});

// POST tambah pesanan
router.post("/", (req, res) => {
  const { id_pelanggan, tanggal, total, status } = req.body;
  const sql = `INSERT INTO pesanan (id_pelanggan, tanggal, total, status) VALUES (?, ?, ?, ?)`;
  db.query(sql, [id_pelanggan, tanggal, total, status], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Pesanan berhasil ditambahkan", id: result.insertId });
  });
});

// PUT update pesanan
router.put("/:id", (req, res) => {
  const { id_pelanggan, tanggal, total, status } = req.body;
  const sql = `UPDATE pesanan SET id_pelanggan = ?, tanggal = ?, total = ?, status = ? WHERE id = ?`;
  db.query(sql, [id_pelanggan, tanggal, total, status, req.params.id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Pesanan berhasil diupdate" });
  });
});

// DELETE hapus pesanan
router.delete("/:id", (req, res) => {
  const sql = `DELETE FROM pesanan WHERE id = ?`;
  db.query(sql, [req.params.id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Pesanan berhasil dihapus" });
  });
});

module.exports = router;
