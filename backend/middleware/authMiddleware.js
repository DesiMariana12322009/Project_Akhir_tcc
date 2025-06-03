import jwt from "jsonwebtoken";

export const verifyToken = (req, res, next) => {
  const authHeader = req.headers.authorization;
  if (!authHeader) return res.status(403).json({ message: "Token required" });

  const token = authHeader.split(" ")[1];
  jwt.verify(token, process.env.JWT_SECRET || "rahasia", (err, decoded) => {
    if (err) return res.status(401).json({ message: "Invalid token" });
    req.user = decoded;
    next();
  });
};

// Middleware to verify admin role
export const verifyAdmin = (req, res, next) => {
  const authHeader = req.headers.authorization;
  if (!authHeader) return res.status(403).json({ message: "Token required" });

  const token = authHeader.split(" ")[1];
  jwt.verify(token, process.env.JWT_SECRET || "rahasia", (err, decoded) => {
    if (err) return res.status(401).json({ message: "Invalid token" });
    if (decoded.role !== 'admin') {
      return res.status(403).json({ message: "Admin access required" });
    }
    req.user = decoded;
    next();
  });
};

// Middleware to verify user or admin role
export const verifyUserOrAdmin = (req, res, next) => {
  const authHeader = req.headers.authorization;
  if (!authHeader) return res.status(403).json({ message: "Token required" });

  const token = authHeader.split(" ")[1];
  jwt.verify(token, process.env.JWT_SECRET || "rahasia", (err, decoded) => {
    if (err) return res.status(401).json({ message: "Invalid token" });
    if (!['user', 'admin'].includes(decoded.role)) {
      return res.status(403).json({ message: "Valid user access required" });
    }
    req.user = decoded;
    next();
  });
};
