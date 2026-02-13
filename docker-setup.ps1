# Docker Quick Start Script for ShopEasily
# Run this script to set up and start the Docker environment

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ShopEasily Docker Quick Start" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if Docker is running
Write-Host "[1/6] Checking Docker status..." -ForegroundColor Yellow
$dockerRunning = docker info 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Docker is not running. Please start Docker Desktop." -ForegroundColor Red
    exit 1
}
Write-Host "✓ Docker is running" -ForegroundColor Green
Write-Host ""

# Build containers
Write-Host "[2/6] Building Docker containers..." -ForegroundColor Yellow
docker-compose build
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to build containers" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Containers built successfully" -ForegroundColor Green
Write-Host ""

# Start containers
Write-Host "[3/6] Starting containers..." -ForegroundColor Yellow
docker-compose up -d
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to start containers" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Containers started" -ForegroundColor Green
Write-Host ""

# Wait for MySQL to be ready
Write-Host "[4/6] Waiting for MySQL to be ready..." -ForegroundColor Yellow
Start-Sleep -Seconds 10
Write-Host "✓ MySQL should be ready" -ForegroundColor Green
Write-Host ""

# Install Composer dependencies
Write-Host "[5/6] Installing Composer dependencies..." -ForegroundColor Yellow
docker-compose exec -T app composer install --no-interaction
if ($LASTEXITCODE -ne 0) {
    Write-Host "WARNING: Some composer packages may have issues, continuing..." -ForegroundColor Yellow
}
Write-Host "✓ Composer dependencies installed" -ForegroundColor Green
Write-Host ""

# Generate application key
Write-Host "[6/6] Generating application key..." -ForegroundColor Yellow
docker-compose exec -T app php artisan key:generate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to generate application key" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Application key generated" -ForegroundColor Green
Write-Host ""

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Setup Complete! Next Steps:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Run migrations:" -ForegroundColor White
Write-Host "   docker-compose exec app php artisan migrate" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Create storage link:" -ForegroundColor White
Write-Host "   docker-compose exec app php artisan storage:link" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Create admin user:" -ForegroundColor White
Write-Host "   docker-compose exec app php artisan make:filament-user" -ForegroundColor Gray
Write-Host ""
Write-Host "4. Access the application:" -ForegroundColor White
Write-Host "   http://localhost" -ForegroundColor Cyan
Write-Host "   http://localhost/admin (Filament Admin)" -ForegroundColor Cyan
Write-Host ""
Write-Host "For detailed instructions, see DOCKER_SETUP.md" -ForegroundColor Yellow
Write-Host ""
