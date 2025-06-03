import express from "express";
import { login, register, loginAdmin, registerAdmin } from "../controllers/authController.js";

const router = express.Router();

// User authentication
router.post("/register", register);
router.post("/login", login);

// Admin authentication
router.post("/admin/login", loginAdmin);
router.post("/admin/register", registerAdmin);

export default router;
