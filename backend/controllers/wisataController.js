import Wisata from "../models/WisataModel.js";
import { Op } from "sequelize";

export const getWisata = async (req, res) => {
  try {
    const data = await Wisata.findAll();
    res.json(data);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

export const getWisataById = async (req, res) => {
  try {
    const data = await Wisata.findByPk(req.params.id);
    if (!data) return res.status(404).json({ message: "Data tidak ditemukan" });
    res.json(data);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

export const createWisata = async (req, res) => {
  try {
    const wisata = await Wisata.create(req.body);
    res.status(201).json(wisata);
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
};

export const updateWisata = async (req, res) => {
  try {
    const data = await Wisata.findByPk(req.params.id);
    if (!data) return res.status(404).json({ message: "Data tidak ditemukan" });

    await data.update(req.body);
    res.json({ message: "Wisata diperbarui" });
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
};

export const deleteWisata = async (req, res) => {
  try {
    const data = await Wisata.findByPk(req.params.id);
    if (!data) return res.status(404).json({ message: "Data tidak ditemukan" });

    await data.destroy();
    res.json({ message: "Wisata dihapus" });
  } catch (err) {
    res.status(400).json({ message: err.message });
  }
};

export const searchWisata = async (req, res) => {
  try {
    const { search, kategori } = req.query;

    // Build search conditions
    let whereConditions = {};

    // If search parameter is provided, search in nama and deskripsi
    if (search) {
      whereConditions[Op.or] = [
        { nama: { [Op.like]: `%${search}%` } },
        { deskripsi: { [Op.like]: `%${search}%` } },
      ];
    }

    // If kategori parameter is provided, filter by kategori
    if (kategori) {
      whereConditions.kategori = { [Op.like]: `%${kategori}%` };
    }

    // If no search parameters provided, return all wisata
    const data = await Wisata.findAll({
      where: whereConditions,
    });

    res.json({
      message: "Search completed successfully",
      total: data.length,
      data: data,
    });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};
