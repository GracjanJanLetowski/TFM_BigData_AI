@echo off
setlocal
title TFM REPOSITORY: INICIAR SISTEMA COMPLETO
color 0B

echo ================================================================
echo   ARRANQUE GLOBAL: BIG DATA E INTELIGENCIA ARTIFICIAL
echo   Autor: Gracjan Jan Letowski
echo ================================================================
echo.

REM 1. Arrancar el Microservicio de IA (Python FastAPI)
echo [1/2] Lanzando Servidor de Inteligencia Artificial (Puerto 8001)...
start "TFM_AI_SERVICE" cmd /c "cd ai_recommender && arrancar.bat"

echo.
echo Esperando a que el motor de IA se estabilice...
timeout /t 5 /nobreak > nul

REM 2. Arrancar la Tienda (Laravel)
echo [2/2] Lanzando Tienda Online (Puerto 8000)...
start "TFM_LARAVEL_STORE" cmd /k "cd onlineStore && php artisan serve"

echo.
echo ================================================================
echo   SISTEMA INICIADO CORRECTAMENTE:
echo   - Frontend/Tienda: http://127.0.0.1:8000
echo   - API de IA:       http://127.0.0.1:8001
echo.
echo   Para cerrar todo de forma segura, usa STOP_SYSTEM.bat
echo ================================================================
echo.

pause
