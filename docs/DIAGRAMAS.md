# Diagramas del Sistema - TFM

Este documento contiene la representación visual de la arquitectura, el flujo de datos y el modelo de base de datos del proyecto.

## 1. Diagrama de Secuencia (Flujo de Recomendación)
Este diagrama describe cómo interactúa el usuario con la plataforma y cómo el backend de Laravel se comunica con el microservicio de IA para obtener recomendaciones personalizadas.

```mermaid
sequenceDiagram
    participant U as Usuario
    participant W as Web App (Frontend)
    participant B as Backend (Laravel)
    participant IA as Microservicio IA (Python)
    participant DB as Base de Datos (MySQL)

    U->>W: Accede a la página principal
    W->>B: Solicita recomendaciones para el usuario
    B->>IA: Solicita IDs de productos recomendados (POST/GET)
    
    rect rgb(240, 240, 240)
        Note over IA, DB: Fase de Procesamiento de IA
        IA->>DB: Consulta historial de compras y valoraciones
        DB-->>IA: Devuelve datos históricos del usuario
        IA->>IA: Ejecuta modelo de filtrado colaborativo
    end

    alt Recomendaciones Encontradas
        IA-->>B: Devuelve lista de IDs recomendados
    else Sin historial suficiente
        IA-->>B: Devuelve lista vacía / populares
    end

    opt Si hay recomendaciones
        B->>DB: Solicita detalles técnicos de los productos IDs
        DB-->>B: Devuelve nombres, precios e imágenes
    end

    B-->>W: Envía página renderizada con recomendaciones
    W-->>U: Muestra productos recomendados en la interfaz
```

---

## 2. Arquitectura de Componentes
Representación de la infraestructura desacoplada entre el núcleo transaccional y el motor de inteligencia artificial.

```mermaid
graph TD
    subgraph "Capa de Cliente"
        User((Usuario))
    end

    subgraph "Backend Framework (PHP 8.1 / Laravel 9)"
        Laravel[Tienda Online - Puerto 8000]
        Controllers[Controladores: Product, Cart, Home]
        Views[Vistas: Blade Templates]
        
        Laravel --> Controllers
        Controllers --> Views
    end

    subgraph "Inteligencia Artificial (Python 3.9 / FastAPI)"
        FastAPI[Microservicio IA - Puerto 8001]
        NLP[Módulo NLP: Análisis de Sentimiento]
        Rec[Modelo: Filtrado Colaborativo]
        Models[(Modelos Entrenados: .pkl / .joblib)]

        FastAPI --> NLP
        FastAPI --> Rec
        NLP --> Models
        Rec --> Models
    end

    subgraph "Capa de Datos"
        DB[(MySQL / MariaDB)]
    end

    %% Relaciones
    User <-->|HTTP/HTTPS| Laravel
    Laravel <==>|APIs REST / JSON| FastAPI
    Controllers <-->|Eloquent ORM| DB
```

---

## 3. Modelo Entidad-Relación (ER)
Estructura de datos optimizada para el ecommerce y la alimentación de los modelos de Machine Learning.

```mermaid
erDiagram
    USERS ||--o{ ORDERS : "realiza"
    USERS ||--o{ COMMENTS : "escribe"
    USERS ||--o{ RATINGS : "valorar"
    
    ORDERS ||--|{ ITEMS : "contiene"
    
    PRODUCTS ||--|{ ITEMS : "incluido en"
    PRODUCTS ||--o{ COMMENTS : "recibe"
    PRODUCTS ||--o{ RATINGS : "puntuado en"

    USERS {
        int id PK
        string name
        string email
        string password
        string role
        decimal balance
    }

    PRODUCTS {
        int id PK
        string name
        text description
        decimal price
        int stock
        string image
    }

    ORDERS {
        int id PK
        int user_id FK
        decimal total
        timestamp created_at
    }

    ITEMS {
        int id PK
        int order_id FK
        int product_id FK
        int quantity
        decimal price
    }

    COMMENTS {
        int id PK
        int user_id FK
        int product_id FK
        text comment
    }

    RATINGS {
        int id PK
        int user_id FK
        int product_id FK
        int rating
    }
```
