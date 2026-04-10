@echo off
title PROYECTO TFM: ONLINE STORE + BIG DATA AI
echo ================================================================
echo   ARRANQUE GLOBAL: E-COMMERCE INTELIGENTE (TFM Big Data)
echo ================================================================
echo.

REM 1. Arrancar el Microservicio de IA (Python)
echo [1/2] Lanzando Servidor de Inteligencia Artificial (Python)...
start "AI_MICROSERVICE" cmd /c "cd ai_recommender && arrancar.bat"

echo Esperando a que la IA se inicialice...
timeout /t 5 /nobreak > nul

REM 2. Arrancar la Tienda (Laravel)
echo [2/2] Lanzando Tienda Online (Laravel)...
start "LARAVEL_STORE" cmd /k "cd onlineStore && php artisan serve"

echo.
echo ================================================================
echo   PROYECTO EN MARCHA:
echo   - Tienda: http://127.0.0.1:8000
echo   - IA API: http://127.0.0.1:8001
echo ================================================================
echo.
echo No cierres las ventanas negras de los servidores si quieres
echo que la web siga funcionando.
echo.
pause
