graph TD
    subgraph "Capa de Cliente"
        A[Usuario / Navegador]
    end
    subgraph "Core Operativo (PHP/Laravel)"
        B[Nginx / Apache] --> C[Laravel Framework]
        C --> D[(MySQL DB)]
    end
    subgraph "Capa de Comunicación (Alta Concurrencia)"
        E{¿Tipo de Tarea?}
        E -- "Síncrona (Ligera)" --> F[REST API HTTP/2]
        E -- "Asíncrona (Pesada)" --> G[Cola de Mensajes / Redis]
    end
    subgraph "Cerebro Analítico (Python/FastAPI)"
        F --> H[Uvicorn / Gunicorn Workers]
        G --> I[Celery / Background Tasks]
        H --> J[Scikit-Learn Models]
        I --> J
        J --> K[(Model Cache / Redis)]
    end
    A --> B
    C --> E
    J -.-> |"Respuesta JSON"| C
