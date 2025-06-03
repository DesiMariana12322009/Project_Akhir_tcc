import express from "express";
import {
  getUsers,
  getUserById,
  createUser,
  updateUser,
  deleteUser
} from "../controllers/userController.js";
import { verifyAdmin } from "../middleware/authMiddleware.js";

const router = express.Router();

// Protected routes (admin authentication required)
router.get("/users", verifyAdmin, getUsers);
router.get("/users/:id", verifyAdmin, getUserById);
router.post("/users", verifyAdmin, createUser);
router.put("/users/:id", verifyAdmin, updateUser);
router.delete("/users/:id", verifyAdmin, deleteUser);

export default router;
