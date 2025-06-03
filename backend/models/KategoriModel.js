import { Sequelize } from "sequelize";
import db from "../config/database.js";

const { DataTypes } = Sequelize;

const Kategori = db.define("kategori", {
  nama: {
    type: DataTypes.STRING,
    allowNull: false,
  },
}, {
  freezeTableName: true,
  timestamps: false
});

export default Kategori;
