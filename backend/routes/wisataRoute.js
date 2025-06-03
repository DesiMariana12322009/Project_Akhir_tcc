import express from "express";
import {
  getWisata,
  getWisataById,
  createWisata,
  updateWisata,
  deleteWisata,
  searchWisata,
} from "../controllers/wisataController.js";
import { verifyAdmin } from "../middleware/authMiddleware.js";

const router = express.Router();

// Public routes (no authentication required)
router.get("/wisata", getWisata);
router.get("/wisata/search", searchWisata);
router.get("/wisata/:id", getWisataById);

// Protected routes (admin authentication required)
router.post("/wisata", verifyAdmin, createWisata);
router.put("/wisata/:id", verifyAdmin, updateWisata);
router.delete("/wisata/:id", verifyAdmin, deleteWisata);

export default router;
