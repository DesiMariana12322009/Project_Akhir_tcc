import Kategori from "../models/KategoriModel.js";

// Get all categories
export const getKategori = async (req, res) => {
  try {
    const kategori = await Kategori.findAll();
    res.json(kategori);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Get category by ID
export const getKategoriById = async (req, res) => {
  try {
    const kategori = await Kategori.findByPk(req.params.id);
    if (!kategori) return res.status(404).json({ message: "Kategori tidak ditemukan" });
    res.json(kategori);
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};

// Create new category
export const createKategori = async (req, res) => {
  try {
    await Kategori.create(req.body);
    res.status(201).json({ message: "Kategori berhasil dibuat" });
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
};

// Update category
export const updateKategori = async (req, res) => {
  try {
    const kategori = await Kategori.findByPk(req.params.id);
    if (!kategori) return res.status(404).json({ message: "Kategori tidak ditemukan" });

    await kategori.update(req.body);
    res.json({ message: "Kategori berhasil diperbarui" });
  } catch (error) {
    res.status(400).json({ message: error.message });
  }
};

// Delete category
export const deleteKategori = async (req, res) => {
  try {
    const kategori = await Kategori.findByPk(req.params.id);
    if (!kategori) return res.status(404).json({ message: "Kategori tidak ditemukan" });

    await kategori.destroy();
    res.json({ message: "Kategori berhasil dihapus" });
  } catch (error) {
    res.status(500).json({ message: error.message });
  }
};
