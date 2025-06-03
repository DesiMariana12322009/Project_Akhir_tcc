# Gunakan image Node.js LTS
FROM node:18-alpine

# Buat directory aplikasi
WORKDIR /usr/src/app

# Copy package.json dan package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy semua file aplikasi
COPY . .

# Expose port yang digunakan
EXPOSE ${PORT}

# Jalankan aplikasi
CMD ["npm", "start"]