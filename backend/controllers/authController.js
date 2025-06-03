import User from "../models/UserModel.js";
import Admin from "../models/AdminModel.js";
import bcrypt from "bcrypt";
import jwt from "jsonwebtoken";
import { Op } from "sequelize";

// Login user
export const login = async (req, res) => {
    try {
        const { username, password } = req.body;

        // Validate input
        if (!username || !password) {
            return res.status(400).json({ 
                message: "Username dan password harus diisi" 
            });
        }

        // Find user by username
        const user = await User.findOne({ where: { username } });
        if (!user) {
            return res.status(401).json({ 
                message: "Username atau password salah" 
            });
        }

        // Verify password
        const isValidPassword = await bcrypt.compare(password, user.password);
        if (!isValidPassword) {
            return res.status(401).json({ 
                message: "Username atau password salah" 
            });
        }

        // Generate JWT token with user role
        const token = jwt.sign(
            { 
                id: user.id, 
                username: user.username, 
                role: 'user' 
            }, 
            process.env.JWT_SECRET || "rahasia",
            { expiresIn: "24h" }
        );

        res.json({
            message: "Login berhasil",
            token,
            user: {
                id: user.id,
                username: user.username,
                email: user.email,
                role: 'user'
            }
        });

    } catch (error) {
        console.error('Login error:', error);
        res.status(500).json({ 
            message: "Terjadi kesalahan saat login" 
        });
    }
};

// Register new user
export const register = async (req, res) => {
    try {
        const { username, email, password } = req.body;

        // Validate input
        if (!username || !email || !password) {
            return res.status(400).json({ 
                message: "Semua field harus diisi" 
            });
        }

        // Check if username or email already exists
        const existingUser = await User.findOne({
            where: {
                [Op.or]: [
                    { username },
                    { email }
                ]
            }
        });

        if (existingUser) {
            return res.status(400).json({ 
                message: "Username atau email sudah digunakan" 
            });
        }

        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Create user
        await User.create({
            username,
            email,
            password: hashedPassword
        });

        res.status(201).json({ 
            message: "User created" 
        });

    } catch (error) {
        console.error('Registration error:', error);
        res.status(500).json({ 
            message: "Terjadi kesalahan saat registrasi" 
        });
    }
};

// Login admin
export const loginAdmin = async (req, res) => {
    try {
        const { username, password } = req.body;

        // Validate input
        if (!username || !password) {
            return res.status(400).json({ 
                message: "Username dan password harus diisi" 
            });
        }

        // Find admin by username
        const admin = await Admin.findOne({ where: { username } });
        if (!admin) {
            return res.status(401).json({ 
                message: "Username atau password salah" 
            });
        }

        // Verify password
        const isValidPassword = await bcrypt.compare(password, admin.password);
        if (!isValidPassword) {
            return res.status(401).json({ 
                message: "Username atau password salah" 
            });
        }

        // Generate JWT token with admin role
        const token = jwt.sign(
            { 
                id: admin.id, 
                username: admin.username, 
                role: 'admin' 
            }, 
            process.env.JWT_SECRET || "rahasia",
            { expiresIn: "24h" }
        );

        res.json({
            message: "Login admin berhasil",
            token,
            user: {
                id: admin.id,
                username: admin.username,
                email: admin.email,
                role: 'admin'
            }
        });

    } catch (error) {
        console.error('Admin login error:', error);
        res.status(500).json({ 
            message: "Terjadi kesalahan saat login admin" 
        });
    }
};

// Register new admin
export const registerAdmin = async (req, res) => {
    try {
        const { username, email, password } = req.body;

        // Validate input
        if (!username || !email || !password) {
            return res.status(400).json({ 
                message: "Semua field harus diisi" 
            });
        }

        // Check if username or email already exists
        const existingAdmin = await Admin.findOne({
            where: {
                [Op.or]: [
                    { username },
                    { email }
                ]
            }
        });

        if (existingAdmin) {
            return res.status(400).json({ 
                message: "Username atau email admin sudah digunakan" 
            });
        }

        // Hash password
        const hashedPassword = await bcrypt.hash(password, 10);

        // Create admin
        await Admin.create({
            username,
            email,
            password: hashedPassword
        });

        res.status(201).json({ 
            message: "Admin berhasil didaftarkan" 
        });

    } catch (error) {
        console.error('Admin registration error:', error);
        res.status(500).json({ 
            message: "Terjadi kesalahan saat registrasi admin" 
        });
    }
};
