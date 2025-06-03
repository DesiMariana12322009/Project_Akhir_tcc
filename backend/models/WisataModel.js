import { DataTypes } from "sequelize";
import db from "../config/database.js";

const Wisata = db.define("wisata", {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  nama: {
    type: DataTypes.STRING,
    allowNull: false,
    validate: {
      notEmpty: true,
      len: [1, 255]
    }
  },
  deskripsi: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  lokasi: {
    type: DataTypes.STRING,
    allowNull: false
  },
  kategori: {
    type: DataTypes.STRING,
    allowNull: false
  },
  url_gambar: {
    type: DataTypes.STRING,
    allowNull: true
  }
}, {
  tableName: 'wisata',
  timestamps: true
});

export default Wisata;
