const express = require("express");
const router = express.Router();
const db = require("../db");

// GET: Ambil semua pelanggan
router.get("/", (req, res) => {
  const sql = "SELECT * FROM pelanggan ORDER BY id DESC";
  db.query(sql, (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(results);
  });
});

// POST: Tambah pelanggan baru
router.post("/", (req, res) => {
  const { nama, no_meja, kontak } = req.body;

  if (!nama || !no_meja) {
    return res.status(400).json({ error: "Nama dan no_meja wajib diisi." });
  }

  const sql = "INSERT INTO pelanggan (nama, no_meja, kontak) VALUES (?, ?, ?)";
  db.query(sql, [nama, no_meja, kontak || null], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Pelanggan berhasil ditambahkan", id: result.insertId });
  });
});


// PUT: Edit pelanggan (opsional)
router.put("/:id", (req, res) => {
  const { id } = req.params;
  const { nama, no_meja, kontak } = req.body;

  const sql = "UPDATE pelanggan SET nama = ?, no_meja = ?, kontak = ? WHERE id = ?";
  db.query(sql, [nama, no_meja, kontak, id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Pelanggan berhasil diperbarui" });
  });
});

// DELETE: Hapus pelanggan (opsional)
router.delete("/:id", (req, res) => {
  const { id } = req.params;

  const sql = "DELETE FROM pelanggan WHERE id = ?";
  db.query(sql, [id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Pelanggan berhasil dihapus" });
  });
});

module.exports = router;
