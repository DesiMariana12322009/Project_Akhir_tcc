import User from "../models/UserModel.js";
import bcrypt from "bcrypt";

// GET all users
export const getUsers = async (req, res) => {
  try {
    const users = await User.findAll({ attributes: { exclude: ['password'] } });
    res.json(users);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// GET user by id
export const getUserById = async (req, res) => {
  try {
    const user = await User.findByPk(req.params.id, { attributes: { exclude: ['password'] } });
    if (!user) return res.status(404).json({ message: "User not found" });
    res.json(user);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// CREATE new user
export const createUser = async (req, res) => {
  const { username, email, password } = req.body;
  try {
    const hashedPassword = await bcrypt.hash(password, 10);
    await User.create({ username, email, password: hashedPassword });
    res.status(201).json({ message: "User created" });
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
};

// UPDATE user by id
export const updateUser = async (req, res) => {
  const { username, email, password } = req.body;
  try {
    const user = await User.findByPk(req.params.id);
    if (!user) return res.status(404).json({ message: "User not found" });

    const updatedData = {
      username: username || user.username,
      email: email || user.email,
      password: password ? await bcrypt.hash(password, 10) : user.password
    };

    await user.update(updatedData);
    res.json({ message: "User updated" });
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
};

// DELETE user
export const deleteUser = async (req, res) => {
  try {
    const user = await User.findByPk(req.params.id);
    if (!user) return res.status(404).json({ message: "User not found" });

    await user.destroy();
    res.json({ message: "User deleted" });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
