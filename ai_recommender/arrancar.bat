@echo off
title MICROSERVICIO IA - BIG DATA (Puerto 8001)
echo ========================================================
echo   INICIANDO MOTOR DE INTELIGENCIA ARTIFICIAL Y BIG DATA
echo ========================================================
echo.

REM Comprobar que Java esta disponible
java -version 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Java no encontrado en PATH. Spark/BigData lo necesita.
    echo Por favor, instala Java 8 o superior.
    pause
    exit /b 1
)

REM Instalar dependencias si no existen (opcional pero seguro)
echo [1/3] Verificando dependencias de Python...
pip install -r requirements.txt --quiet --no-warn-script-location

echo [2/3] Configurando el entorno...
set PYTHONPATH=%PYTHONPATH%;.

echo [3/3] Arrancando Servidor FastAPI en http://127.0.0.1:8001
echo.
echo Modulos activos: 
echo - Recomendador (Sklearn)
echo - Forecasting (Regression)
echo - NLP Sentiment (Logic)
echo - Clustering (K-Means)
echo ========================================================
echo.

uvicorn main:app --host 127.0.0.1 --port 8001 --reload
pause
