import express from "express";
import cors from "cors";
import dotenv from "dotenv";
import db from "./config/database.js";

// Import semua model agar tersinkronisasi dengan db.sync()
import "./models/WisataModel.js";
import "./models/UserModel.js";
import "./models/KategoriModel.js";
import "./models/AdminModel.js"; // ✅ Tambah model Admin

// Import semua route
import wisataRoutes from "./routes/wisataRoute.js";
import userRoutes from "./routes/userRoute.js";
import kategoriRoutes from "./routes/kategoriRoute.js";
import adminRoutes from "./routes/adminRoute.js";
import authRoutes from "./routes/authRoute.js";

dotenv.config();
const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());

// Health check route
app.get('/', (req, res) => {
  res.json({
    message: "✅ Wisata Indonesia API Server is running!",
    status: "OK",
    timestamp: new Date().toISOString()
  });
});

// Routes
app.use('/api', authRoutes); // Auth routes directly under /api (register, login, admin/register, admin/login)
app.use('/api', wisataRoutes); // All wisata routes under /api/wisata
app.use('/api', userRoutes); // All user routes under /api/users
app.use('/api', kategoriRoutes); // All kategori routes under /api/kategori
app.use('/api', adminRoutes); // All admin routes under /api/admin

// Jalankan server dan koneksi database
(async () => {
  try {
    console.log("🔄 Connecting to database...");
    await db.authenticate();
    console.log("✅ Database connected successfully!");

    console.log("🔄 Syncing database tables...");
    // Force sync in development, alter in production
    const syncOptions = process.env.NODE_ENV === 'production' 
      ? { alter: true } 
      : { force: false, alter: true };
      
    await db.sync(syncOptions);
    console.log("✅ Database tables synced successfully!");
    
    // Log all registered models
    const models = Object.keys(db.models);
    console.log(`📋 Registered models: ${models.join(', ')}`);

    app.listen(PORT, () => {
      console.log(`🚀 Server running at http://localhost:${PORT}`);
      console.log(`📱 Environment: ${process.env.NODE_ENV || 'development'}`);
      console.log(`💾 Database: MySQL`);
      console.log(`🔗 Health check: http://localhost:${PORT}/`);
    });
  } catch (error) {
    console.error("❌ Database connection error:", error);
    console.error("💡 Details:", error.message);
    console.error("🔧 Stack:", error.stack);
    
    // Exit process if database connection fails
    process.exit(1);
  }
})();
