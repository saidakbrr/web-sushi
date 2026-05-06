const express = require("express");
const router = express.Router();
const db = require("../db");

// GET: Ambil semua detail pesanan dengan info pelanggan dan menu
router.get("/", (req, res) => {
  const sql = `
    SELECT dp.id, dp.id_pesanan, dp.id_menu, dp.jumlah, dp.subtotal,
    m.nama AS nama_menu,
    pl.nama AS nama_pelanggan, pl.no_meja
    FROM detail_pesanan dp
    JOIN menu m ON dp.id_menu = m.id
    JOIN pesanan p ON dp.id_pesanan = p.id
    JOIN pelanggan pl ON p.id_pelanggan = pl.id
    ORDER BY dp.id DESC
  `;
  db.query(sql, (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(results);
  });
});

// POST: Tambah detail pesanan
router.post("/", (req, res) => {
  const { id_pesanan, id_menu, jumlah, subtotal } = req.body;

  console.log("POST /api/detailpesanan");
  console.log("Data Diterima:", req.body);

  const sql = `
    INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal)
    VALUES (?, ?, ?, ?)
  `;
  db.query(sql, [id_pesanan, id_menu, jumlah, subtotal], (err, result) => {
    if (err) {
      console.error("SQL Error:", err.message);
      return res.status(500).json({ error: err.message });
    }
    res.json({ message: "Detail pesanan berhasil ditambahkan" });
  });
});

router.put("/:id", (req, res) => {
  const { id_menu, jumlah, subtotal } = req.body;

  console.log("PUT /api/detailpesanan/:id");
  console.log("ID:", req.params.id);
  console.log("Data Diterima:", req.body); // ✅ log isi data

  const sql = `
    UPDATE detail_pesanan 
    SET id_menu = ?, jumlah = ?, subtotal = ?
    WHERE id = ?
  `;
  db.query(sql, [id_menu, jumlah, subtotal, req.params.id], (err, result) => {
    if (err) {
      console.error("SQL Error:", err.message); // log error
      return res.status(500).json({ error: err.message });
    }
    res.json({ message: "Detail pesanan berhasil diperbarui" });
  });
});

// DELETE: Hapus detail pesanan
router.delete("/:id", (req, res) => {
  const sql = "DELETE FROM detail_pesanan WHERE id = ?";
  db.query(sql, [req.params.id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ message: "Detail pesanan berhasil dihapus" });
  });
});



module.exports = router;
