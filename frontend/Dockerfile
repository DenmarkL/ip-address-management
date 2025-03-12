# Use Node.js image
FROM node:18-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy the rest of the app
COPY . .

# Expose Vite's default dev port
EXPOSE 5173

# Run Vite in development mode
CMD ["npm", "run", "dev", "--", "--host"]
