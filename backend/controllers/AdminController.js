import Admin from "../models/AdminModel.js";
import bcrypt from "bcrypt";

// GET all admins
export const getAdmins = async (req, res) => {
  try {
    const admins = await Admin.findAll({ attributes: { exclude: ['password'] } });
    res.json(admins);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// GET admin by ID
export const getAdminById = async (req, res) => {
  try {
    const admin = await Admin.findByPk(req.params.id, { attributes: { exclude: ['password'] } });
    if (!admin) return res.status(404).json({ message: "Admin not found" });
    res.json(admin);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// CREATE new admin
export const createAdmin = async (req, res) => {
  const { username, email, password } = req.body;
  try {
    const hashedPassword = await bcrypt.hash(password, 10);
    await Admin.create({ username, email, password: hashedPassword });
    res.status(201).json({ message: "Admin created" });
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
};

// UPDATE admin
export const updateAdmin = async (req, res) => {
  const { username, email, password } = req.body;
  try {
    const admin = await Admin.findByPk(req.params.id);
    if (!admin) return res.status(404).json({ message: "Admin not found" });

    const updatedData = {
      username: username || admin.username,
      email: email || admin.email,
      password: password ? await bcrypt.hash(password, 10) : admin.password
    };

    await admin.update(updatedData);
    res.json({ message: "Admin updated" });
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
};

// DELETE admin
export const deleteAdmin = async (req, res) => {
  try {
    const admin = await Admin.findByPk(req.params.id);
    if (!admin) return res.status(404).json({ message: "Admin not found" });

    await admin.destroy();
    res.json({ message: "Admin deleted" });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
