import { Sequelize } from "sequelize";
import dotenv from "dotenv";
dotenv.config();

const db = new Sequelize(
  process.env.DB_NAME || "galeri_db",
  process.env.DB_USER || "root",
  process.env.DB_PASS || "",
  {
    host: process.env.DB_HOST || "localhost",
    dialect: "mysql",
    logging: console.log, // Enable logging in development
    pool: {
      max: 5,
      min: 0,
      acquire: 30000,
      idle: 10000
    },
    define: {
      timestamps: true, // Automatically add createdAt and updatedAt
      underscored: false, // Use camelCase instead of snake_case
      freezeTableName: true // Don't pluralize table names
    }
  }
);

export default db;
