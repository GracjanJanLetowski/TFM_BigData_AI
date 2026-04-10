# 🚀 Trabajo Fin de Máster: Big Data e Inteligencia Artificial

Este proyecto representa una solución integral de **E-commerce impulsada por Inteligencia Artificial y Big Data**, desarrollada como proyecto final de Máster. El sistema integra una plataforma de ventas transaccional con un motor analítico avanzado basado en microservicios.

---

## 👨‍💻 Autor
**Gracjan Jan Letowski**  
📧 [gracjanjanle@gmail.com](mailto:gracjanjanle@gmail.com)  

---

## 🏗️ Arquitectura del Sistema

El proyecto utiliza una arquitectura de microservicios para separar la operativa comercial de la carga computacional analítica:

1.  **OnlineStore (Core Operativo)**:
    *   **Stack**: PHP (Laravel 9) + MySQL + Bootstrap.
    *   **Responsabilidad**: Gestión de tienda, catálogo, autenticación de usuarios, sistema de pedidos y Dashboard administrativo.
    *   **Puerto**: `8000`

2.  **AI_Recommender (Cerebro Analítico)**:
    *   **Stack**: Python (FastAPI) + Scikit-Learn + Pandas + NumPy.
    *   **Responsabilidad**: Procesamiento de datos para recomendaciones, predicciones de ventas, segmentación de clientes y análisis de sentimiento.
    *   **Puerto**: `8001`

---

## 🧠 Capacidades de IA e Inteligencia de Negocio

La plataforma implementa 5 ejes fundamentales de analítica avanzada:

### 1. Recomendador Inteligente (AI Discovery)
Algoritmo de **Filtro Colaborativo** que analiza el comportamiento de los usuarios para sugerir productos personalizados, aumentando la tasa de conversión.

### 2. Predicción de Ventas (AI Forecasting)
Modelado mediante **Regresión Lineal** que proyecta las ventas de los próximos 7 días basándose en el histórico reciente, permitiendo una mejor planificación financiera.

### 3. NLP - Análisis de Sentimiento
Procesamiento de Lenguaje Natural sobre las reseñas de productos. Clasifica de forma automática el feedback de los clientes en **Positivo, Neutral o Negativo**.

### 4. Segmentación de Clientes (Clustering)
Uso del algoritmo **K-Means** para agrupar clientes según su valor (Gasto, Frecuencia). Permite identificar a los usuarios VIP y enfocar campañas de marketing.

### 5. Optimización de Inventario (Predictive Logistics)
Cálculo dinámico de la **Velocidad de Venta**. El sistema genera alertas de stock bajo antes de que se agote el producto, basándose en el ritmo real de compra.

---

## 🛠️ Instalación y Configuración

### Requisitos Previos
*   PHP 8.1+ & Composer
*   Python 3.9+ & pip
*   MySQL (XAMPP / Laragon / Docker)

### Guía Rápida de Arranque
Para facilitar las pruebas, se incluye un script que lanza ambos servicios simultáneamente:
1.  Configura el `.env` en la carpeta `onlineStore` con tu base de datos MySQL.
2.  Ejecuta `INICIAR_TODO.bat` en la raíz del proyecto.

---

## 💎 Valor Académico y Tecnológico

Este TFM demuestra la viabilidad de integrar **Data Science** en entornos productivos mediante:
*   **Desacoplamiento**: Separación de servicios para escalabilidad independiente.
*   **Analíticas en Tiempo Real**: Dashboards que se actualizan con cada interacción del usuario.
*   **Algoritmos de ML**: Implementación práctica de modelos supervisados y no supervisados.

---
*Proyecto desarrollado para el Máster en Big Data e Inteligencia Artificial.*
