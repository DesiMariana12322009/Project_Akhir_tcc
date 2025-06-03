import express from "express";
import {
  getAdmins,
  getAdminById,
  createAdmin,
  updateAdmin,
  deleteAdmin,
} from "../controllers/AdminController.js";
import { verifyAdmin } from "../middleware/authMiddleware.js";

const router = express.Router();

// Protected routes (admin authentication required)
router.get("/admin", verifyAdmin, getAdmins);
router.get("/admin/:id", verifyAdmin, getAdminById);
router.post("/admin", verifyAdmin, createAdmin);
router.put("/admin/:id", verifyAdmin, updateAdmin);
router.delete("/admin/:id", verifyAdmin, deleteAdmin);

export default router;
