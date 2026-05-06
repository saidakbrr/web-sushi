const express = require("express");
const router = express.Router();
const db = require("../db");

const multer = require('multer');
const path = require('path');

const storage = multer.diskStorage({
  destination: path.join(__dirname, "../../uploads"), 
  filename: (req, file, cb) => {
    cb(null, Date.now() + path.extname(file.originalname));
  }
});


const upload = multer({ storage: storage });

// GET: Ambil semua menu
router.get("/", (req, res) => {
  const sql = "SELECT * FROM menu ORDER BY id DESC";
  db.query(sql, (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(results);
  });
});

// GET: Ambil satu menu berdasarkan ID
router.get("/:id", (req, res) => {
  const { id } = req.params;
  const sql = "SELECT * FROM menu WHERE id = ?";
  db.query(sql, [id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    if (result.length === 0) return res.status(404).json({ error: "Menu tidak ditemukan" });
    res.json(result[0]);
  });
});



// POST: Tambah menu baru dengan upload gambar
router.post("/", upload.single("foto"), (req, res) => {
  const { nama, deskripsi, harga, kategori } = req.body;
  const foto = req.file ? req.file.filename : '';

  if (!nama || !harga || !kategori) {
    return res.status(400).json({ error: "Nama, harga, dan kategori wajib diisi." });
  }

  const sql = `INSERT INTO menu (nama, deskripsi, harga, kategori, foto) VALUES (?, ?, ?, ?, ?)`;
  db.query(sql, [nama, deskripsi || '', harga, kategori, foto], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Menu berhasil ditambahkan", id: result.insertId });
  });
});


// PUT: Edit menu
router.put("/:id", (req, res) => {
  const { id } = req.params;
  const { nama, deskripsi, harga, kategori, foto } = req.body;

  const sql = `UPDATE menu SET nama = ?, deskripsi = ?, harga = ?, kategori = ?, foto = ? WHERE id = ?`;
  db.query(sql, [nama, deskripsi, harga, kategori, foto, id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Menu berhasil diperbarui" });
  });
});

// DELETE: Hapus menu
router.delete("/:id", (req, res) => {
  const { id } = req.params;

  const sql = `DELETE FROM menu WHERE id = ?`;
  db.query(sql, [id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Menu berhasil dihapus" });
  });
});

module.exports = router;
