# Online Store — Frontend & Gestión Transaccional

Este es el núcleo de la tienda online del proyecto `Laravel Entrega Final`. Ha sido extendido para actuar como **Consumer** de una API de Inteligencia Artificial externa.

## 🚀 Características Principales

*   **🛒 E-commerce Completo**: Catálogo, Carrito de compra y Gestión de Pedidos.
*   **👤 Roles de Usuario**: Diferenciación entre Clientes y Administradores.
*   **🌐 Multi-idioma**: Soporte completo para Español e Inglés.
*   **🎨 UI Moderna**: Integración con Bootstrap 5 y Chart.js para paneles visuales.
*   **🔌 Inteligencia Externa**: Integración vía HTTP con el microservicio `ai_recommender`.

## ⚙️ Integración con IA y Big Data

Laravel consume cuatro tipos de inteligencia del servidor Python:
1.  **Recomendaciones**: Se inyectan en la Home mediante `HomeController`.
2.  **Analítica Admin**: Se visualizan gráficos de Predicción y Afinidad en `AdminDashboardController`.
3.  **NLP**: El detalle de cada producto muestra el sentimiento analizado de sus comentarios.
4.  **CRM Inteligente**: Segmentación de usuarios por comportamiento en el panel de control.

## 🛠️ Instalación Técnica

1.  Asegúrate de tener un servidor PHP (XAMPP/WAMP) y Composer instalado.
2.  Copia el archivo `.env.example` a `.env` y configura el nombre de tu base de datos.
3.  Ejecuta:
    ```bash
    composer install
    php artisan key:generate
    php artisan migrate:fresh --seed
    php artisan serve
    ```

---
*Este componente se encarga de la captura de datos (captación de valoraciones y compras) que posteriormente son alimentados al motor de Big Data.*
