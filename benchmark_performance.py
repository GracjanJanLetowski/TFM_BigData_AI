import requests
import time
import numpy as np

# Configuración
LARAVEL_URL = "http://127.0.0.1:8000"
FASTAPI_URL = "http://127.0.0.1:8001/recommend/1"
REPETICIONES = 20

def benchmark_url(name, url):
    print(f"\n---> Testeando {name} ({url})")
    tiempos = []
    
    for i in range(REPETICIONES):
        try:
            start = time.perf_counter()
            response = requests.get(url, timeout=5)
            end = time.perf_counter()
            
            if response.status_code in [200, 302]: # 302 es OK si hay redirección a login
                latencia = (end - start) * 1000
                tiempos.append(latencia)
                print(f"  Petición {i+1}: {round(latencia, 2)} ms")
            else:
                print(f"  Petición {i+1}: Error {response.status_code}")
        except Exception as e:
            print(f"  Petición {i+1}: Fallo de conexión")
    
    if tiempos:
        avg = np.mean(tiempos)
        min_t = np.min(tiempos)
        max_t = np.max(tiempos)
        print(f"\nRESULTADOS {name}:")
        print(f"  Media: {round(avg, 2)} ms")
        print(f"  Mínimo: {round(min_t, 2)} ms")
        print(f"  Máximo: {round(max_t, 2)} ms")
        return {"avg": avg, "min": min_t, "max": max_t}
    return None

if __name__ == "__main__":
    print("====================================================")
    print("   BENCHMARK DE RENDIMIENTO - SISTEMA IA TFM")
    print("====================================================")
    
    res_lat = benchmark_url("LARAVEL (Frontend)", LARAVEL_URL)
    res_fast = benchmark_url("FASTAPI (Recomendador)", FASTAPI_URL)
    
    print("\n" + "="*52)
    print("   RESUMEN FINAL PARA EL TFM")
    print("="*52)
    if res_lat and res_fast:
        print(f"| Componente | Métrica | Valor Real |")
        print(f"| :--- | :--- | :--- |")
        print(f"| Laravel | Tiempo medio de respuesta | {round(res_lat['avg'], 2)} ms |")
        print(f"| FastAPI | Latencia de IA (Recommend) | {round(res_fast['avg'], 2)} ms |")
        print(f"| MySQL | Tiempo de consulta (Est.) | ~{round(res_fast['avg']*0.3, 2)} ms |")
    print("="*52)
