const express = require('express');
const router = express.Router();
const db = require('../db');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const { authenticateToken, authorizeRoles } = require('../middleware/auth');
const SECRET = 'RAHASIA'; // samakan dengan index.js

// === 🔓 REGISTER (Publik, tanpa token) ===
router.post('/register', async (req, res) => {
  const { username, password } = req.body;

  if (!username || !password)
    return res.status(400).json({ message: 'Username dan password wajib' });
  if (username.length < 3)
    return res.status(400).json({ message: 'Username minimal 3 karakter' });
  if (password.length < 6)
    return res.status(400).json({ message: 'Password minimal 6 karakter' });

  db.query('SELECT id FROM users WHERE username = ?', [username], async (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    if (results.length > 0)
      return res.status(400).json({ message: 'Username sudah digunakan' });

    const hashed = await bcrypt.hash(password, 10);
    db.query(
      'INSERT INTO users (username, password, role) VALUES (?, ?, ?)',
      [username, hashed, 'user'],
      (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.status(201).json({ message: 'Registrasi berhasil', id: result.insertId });
      }
    );
  });
});

// === 🔐 LOGIN ===
router.post('/login', (req, res) => {
  const { username, password } = req.body;
  const sql = 'SELECT * FROM users WHERE username = ?';

  db.query(sql, [username], async (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    if (results.length === 0)
      return res.status(401).json({ message: 'Username salah' });

    const user = results[0];
    const match = await bcrypt.compare(password, user.password);
    if (!match)
      return res.status(401).json({ message: 'Password salah' });

    const token = jwt.sign({ id: user.id, role: user.role }, SECRET, { expiresIn: '1h' });

    res.json({
      message: "Login berhasil",
      token,
      user: {
        id: user.id,
        username: user.username,
        role: user.role
      }
    });
  });
});

// === 👤 GET /me (profil berdasarkan token) ===
router.get('/me', authenticateToken, (req, res) => {
  const sql = 'SELECT id, username, role FROM users WHERE id = ?';
  db.query(sql, [req.user.id], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    if (result.length === 0) return res.status(404).json({ message: 'User tidak ditemukan' });
    res.json(result[0]);
  });
});

// === 👮 ADMIN ONLY: GET semua user ===
router.get('/', authenticateToken, authorizeRoles('admin'), (req, res) => {
  db.query('SELECT id, username, role FROM users', (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(results);
  });
});

// === 👮 ADMIN ONLY: GET user by ID ===
router.get('/:id', authenticateToken, authorizeRoles('admin'), (req, res) => {
  const sql = 'SELECT id, username, role FROM users WHERE id = ?';
  db.query(sql, [req.params.id], (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    if (results.length === 0) return res.status(404).json({ message: 'User not found' });
    res.json(results[0]);
  });
});

// === 👮 ADMIN ONLY: Tambah user manual ===
router.post('/', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  const { username, password, role } = req.body;

  if (!username || !password || !role)
    return res.status(400).json({ message: 'Username, password, dan role wajib' });

  db.query('SELECT id FROM users WHERE username = ?', [username], async (err, results) => {
    if (err) return res.status(500).json({ error: err.message });
    if (results.length > 0)
      return res.status(400).json({ message: 'Username sudah digunakan' });

    const hashedPassword = await bcrypt.hash(password, 10);
    db.query(
      'INSERT INTO users (username, password, role) VALUES (?, ?, ?)',
      [username, hashedPassword, role],
      (err, result) => {
        if (err) return res.status(500).json({ error: err.message });
        res.status(201).json({ message: 'User ditambahkan', id: result.insertId });
      }
    );
  });
});

// === 👮 ADMIN ONLY: Update user ===
router.put('/:id', authenticateToken, authorizeRoles('admin'), async (req, res) => {
  const { username, password, role } = req.body;
  const hashed = await bcrypt.hash(password, 10);

  db.query(
    'UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?',
    [username, hashed, role, req.params.id],
    (err, result) => {
      if (err) return res.status(500).json({ error: err.message });
      if (result.affectedRows === 0)
        return res.status(404).json({ message: 'User tidak ditemukan' });
      res.json({ message: 'User berhasil diperbarui' });
    }
  );
});

// === 👮 ADMIN ONLY: Hapus user, tapi cegah hapus diri sendiri ===
router.delete('/:id', authenticateToken, authorizeRoles('admin'), (req, res) => {
  const userId = parseInt(req.params.id);
  if (userId === req.user.id) {
    return res.status(403).json({ message: '❌ Tidak bisa menghapus akun sendiri.' });
  }

  db.query('DELETE FROM users WHERE id = ?', [userId], (err, result) => {
    if (err) return res.status(500).json({ error: err.message });
    if (result.affectedRows === 0)
      return res.status(404).json({ message: 'User tidak ditemukan' });
    res.json({ message: '✅ User berhasil dihapus' });
  });
});

module.exports = router;
