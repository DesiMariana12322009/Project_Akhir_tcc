import express from "express";
import { getKategori, getKategoriById, createKategori, updateKategori, deleteKategori } from "../controllers/KategoriController.js";
import { verifyAdmin } from "../middleware/authMiddleware.js";

const router = express.Router();

// Public routes (no authentication required)
router.get("/kategori", getKategori);
router.get("/kategori/:id", getKategoriById);

// Protected routes (admin authentication required)
router.post("/kategori", verifyAdmin, createKategori);
router.put("/kategori/:id", verifyAdmin, updateKategori);
router.delete("/kategori/:id", verifyAdmin, deleteKategori);

export default router;
