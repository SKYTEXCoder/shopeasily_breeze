# Docker Management Commands for ShopEasily
# This script provides easy access to common Docker commands

param(
    [Parameter(Position=0)]
    [ValidateSet('start', 'stop', 'restart', 'logs', 'migrate', 'seed', 'fresh', 'bash', 'status', 'help')]
    [string]$Command = 'help'
)

switch ($Command) {
    'start' {
        Write-Host "Starting all containers..." -ForegroundColor Cyan
        docker-compose up -d
        Write-Host "✓ Containers started" -ForegroundColor Green
    }
    
    'stop' {
        Write-Host "Stopping all containers..." -ForegroundColor Cyan
        docker-compose down
        Write-Host "✓ Containers stopped" -ForegroundColor Green
    }
    
    'restart' {
        Write-Host "Restarting all containers..." -ForegroundColor Cyan
        docker-compose restart
        Write-Host "✓ Containers restarted" -ForegroundColor Green
    }
    
    'logs' {
        Write-Host "Showing container logs (Ctrl+C to exit)..." -ForegroundColor Cyan
        docker-compose logs -f
    }
    
    'migrate' {
        Write-Host "Running database migrations..." -ForegroundColor Cyan
        docker-compose exec app php artisan migrate
    }
    
    'seed' {
        Write-Host "Seeding database..." -ForegroundColor Cyan
        docker-compose exec app php artisan db:seed
    }
    
    'fresh' {
        Write-Host "Fresh migration with seed (WARNING: This will delete all data!)..." -ForegroundColor Red
        $confirmation = Read-Host "Are you sure? Type 'yes' to continue"
        if ($confirmation -eq 'yes') {
            docker-compose exec app php artisan migrate:fresh --seed
            Write-Host "✓ Fresh migration completed" -ForegroundColor Green
        } else {
            Write-Host "Operation cancelled" -ForegroundColor Yellow
        }
    }
    
    'bash' {
        Write-Host "Opening bash shell in app container..." -ForegroundColor Cyan
        docker-compose exec app bash
    }
    
    'status' {
        Write-Host "Container status:" -ForegroundColor Cyan
        docker-compose ps
    }
    
    'help' {
        Write-Host ""
        Write-Host "ShopEasily Docker Management" -ForegroundColor Cyan
        Write-Host "=============================" -ForegroundColor Cyan
        Write-Host ""
        Write-Host "Usage: .\docker.ps1 <command>" -ForegroundColor White
        Write-Host ""
        Write-Host "Available commands:" -ForegroundColor Yellow
        Write-Host "  start    - Start all containers" -ForegroundColor White
        Write-Host "  stop     - Stop all containers" -ForegroundColor White
        Write-Host "  restart  - Restart all containers" -ForegroundColor White
        Write-Host "  logs     - Show container logs" -ForegroundColor White
        Write-Host "  migrate  - Run database migrations" -ForegroundColor White
        Write-Host "  seed     - Seed the database" -ForegroundColor White
        Write-Host "  fresh    - Fresh migration with seed (deletes all data)" -ForegroundColor White
        Write-Host "  bash     - Open bash shell in app container" -ForegroundColor White
        Write-Host "  status   - Show container status" -ForegroundColor White
        Write-Host "  help     - Show this help message" -ForegroundColor White
        Write-Host ""
        Write-Host "Examples:" -ForegroundColor Yellow
        Write-Host "  .\docker.ps1 start" -ForegroundColor Gray
        Write-Host "  .\docker.ps1 logs" -ForegroundColor Gray
        Write-Host "  .\docker.ps1 migrate" -ForegroundColor Gray
        Write-Host ""
    }
}
