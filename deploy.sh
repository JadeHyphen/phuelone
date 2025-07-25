#!/bin/bash

# Deployment script for Phuelone

# Step 1: Pull latest changes
echo "Pulling latest changes from repository..."
git pull origin main

# Step 2: Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Step 3: Optimize framework
echo "Optimizing framework..."
php phuelone optimize

# Step 4: Set permissions
echo "Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Step 5: Run migrations
echo "Running migrations..."
php vendor/bin/phpunit --migrate || echo "Migration command failed. Please run manually."

# Step 6: Restart server
echo "Restarting server..."
sudo systemctl restart apache2 || sudo systemctl restart nginx

echo "Deployment complete!"
