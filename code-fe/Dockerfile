FROM node:18-alpine

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install
RUN npm install axios @headlessui/vue @heroicons/vue @tailwindcss/forms

# Copy project files
COPY . .

# Build the app
RUN npm run build

# Expose port 5173
EXPOSE 5173

# Start the application
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]