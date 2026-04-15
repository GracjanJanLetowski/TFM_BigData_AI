@echo off
setlocal
title TFM REPOSITORY: APAGAR SISTEMA
color 0C

echo ================================================================
echo   DETENIENDO SISTEMA: BIG DATA E INTELIGENCIA ARTIFICIAL
echo ================================================================
echo.

echo [1/2] Deteniendo procesos en Puerto 8000 (Laravel)...
for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8000 ^| findstr LISTENING') do (
    echo Matando proceso PID %%a...
    taskkill /F /PID %%a 2>nul
)

echo [2/2] Deteniendo procesos en Puerto 8001 (IA Python)...
for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8001 ^| findstr LISTENING') do (
    echo Matando proceso PID %%a...
    taskkill /F /PID %%a 2>nul
)

echo.
echo ================================================================
echo   SISTEMA DETENIDO.
echo ================================================================
echo.
pause
