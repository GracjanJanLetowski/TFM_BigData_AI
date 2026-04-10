# AI & Big Data Engine — Microservicio de Inteligencia

Este es el cerebro analítico del proyecto. Actúa como un servidor de **Inteligencia de Negocio** que procesa grandes volúmenes de datos transaccionales para convertirlos en conocimiento accionable.

## 🚀 Módulos de Inteligencia

El microservicio está estructurado en 4 pilares:

### 1. Motor de Recomendación (Sklearn)
Implementa un algoritmo de Filtrado Colaborativo que calcula la similitud entre los perfiles de usuario y las características de los productos.
*   **Algoritmo**: Jaccard Similarity / KNN simplificado.
*   **Input**: `ratings` (explícito) + `orders` (implícito).

### 2. Marketing Predictivo (Clustering)
Utiliza Machine Learning para encontrar patrones ocultos en la base de clientes.
*   **Algoritmo**: **K-Means Clustering**.
*   **Clusters**: VIP (Alto valor), Clientes Fieles, Ocasionales.

### 3. NLP & Análisis de Sentimiento
Procesa los comentarios de texto de la tienda para automatizar la gestión de calidad.
*   **Técnica**: Tokenización y Análisis de Polaridad léxica en Español.

### 4. Forecasting & Stock Analytics
Predicción de series temporales para gestión de almacén y ventas.
*   **Algoritmo**: Regresión Lineal Polinómica.

## 🛠️ Requisitos e Instalación

1.  Asegúrate de tener Python 3.9+ instalado.
2.  Instala las dependencias de Big Data:
    ```bash
    pip install -r requirements.txt
    ```
3.  Arranca el servidor:
    ```bash
    uvicorn main:app --host 127.0.0.1 --port 8001
    ```

## 🔌 API Endpoints (JSON)

| Ruta | Uso |
|:---|:---|
| `GET /recommend/{user_id}` | Sistema de recomendaciones personalizadas. |
| `GET /admin/stats` | Datos de predicción de ventas y afinidad de productos. |
| `GET /admin/segmentation` | Datos del algoritmo K-Means. |
| `GET /admin/inventory-risk` | Informe de productos con riesgo de stock. |
| `GET /sentiment/analyze/{p_id}` | Resultado del análisis de sentimiento NLP. |

---
*Este microservicio está diseñado para ser escalable. Aunque actualmente usa una base de datos centralizada, su arquitectura permite integrarlo con sistemas de streaming como Kafka o Spark Streaming en el futuro.*
