import os
import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from fastapi import FastAPI, HTTPException
from sklearn.cluster import KMeans
import logging
from datetime import datetime

# ─────────────────────────────────────────────
#  Configuración de Monitorización (Logs)
# ─────────────────────────────────────────────
ai_logger = logging.getLogger("ai_monitoring")
ai_logger.setLevel(logging.INFO)
# Evitar duplicados si se recarga el script
if not ai_logger.handlers:
    file_handler = logging.FileHandler("ai_predictions.log")
    file_handler.setFormatter(logging.Formatter("%(asctime)s - %(message)s", datefmt="%Y-%m-%d %H:%M:%S"))
    ai_logger.addHandler(file_handler)

def log_prediction(module, details):
    ai_logger.info(f"[{module.upper()}] {details}")
    for handler in ai_logger.handlers:
        handler.flush()

# ─────────────────────────────────────────────
#  Configuración de la Base de Datos
# ─────────────────────────────────────────────
DB_USER = os.getenv("DB_USERNAME", "root")
DB_PASS = os.getenv("DB_PASSWORD", "1234")
DB_HOST = os.getenv("DB_HOST", "127.0.0.1")
DB_NAME = os.getenv("DB_DATABASE", "proyectotienda")

DATABASE_URL = f"mysql+mysqlconnector://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}"
engine = create_engine(DATABASE_URL)

app = FastAPI(title="AI & Big Data Intelligence Service")

@app.get("/")
def home():
    return {"status": "online", "modules": ["Sentiment", "Clustering", "Recs", "Forecasting"]}

# ─────────────────────────────────────────────
#  MÓDULO 1: NLP — Análisis de Sentimiento
# ─────────────────────────────────────────────
def analyze_simple_sentiment(text):
    """
    Analizador léxico de sentimiento para español.
    Basado en palabras clave para evitar descargas de modelos pesados en el TFM.
    """
    if not text: return 0
    text = text.lower()
    pos = ['excelente', 'increible', 'encanta', 'calidad', 'bueno', 'recomendado', 'espectacular', 'gracias', 'mejor']
    neg = ['horrible', 'mala', 'decepción', 'tarde', 'no', 'malo', 'peor', 'fallo', 'error', 'problema', 'caro']
    
    score = 0
    for w in pos: score += text.count(w)
    for w in neg: score -= text.count(w)
    
    # Normalizar entre -1 y 1
    if score > 0: return 1  # Positivo
    if score < 0: return -1 # Negativo
    return 0                # Neutral

@app.get("/sentiment/analyze/{product_id}")
def sentiment_analysis(product_id: int):
    try:
        query = f"SELECT comment FROM comments WHERE product_id = {product_id}"
        df = pd.read_sql(query, engine)
        if df.empty:
            return {"sentiment": "no_data", "score": 0, "count": 0}
        
        df['score'] = df['comment'].apply(analyze_simple_sentiment)
        avg_score = df['score'].mean()
        
        label = "Neutral"
        if avg_score > 0.2: label = "Positivo"
        elif avg_score < -0.2: label = "Negativo"
        
        return {
            "sentiment": label,
            "score": round(avg_score, 2),
            "count": len(df)
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ─────────────────────────────────────────────
#  MÓDULO 2: Big Data — Segmentación (Clustering)
# ─────────────────────────────────────────────
@app.get("/admin/segmentation")
def client_segmentation():
    try:
        # Extraer métricas por usuario (Monetario y Frecuencia)
        query = """
            SELECT user_id, 
                   COUNT(*) as orders_count, 
                   SUM(total) as total_spent
            FROM orders
            GROUP BY user_id
        """
        df = pd.read_sql(query, engine)
        
        if len(df) < 3: # Necesitamos al menos unos pocos para clusterizar
            return {"segments": [], "type": "insufficient_data"}

        # Algoritmo de ML: K-Means (Clustering)
        X = df[['orders_count', 'total_spent']].values
        kmeans = KMeans(n_clusters=min(3, len(df)), random_state=42, n_init=10)
        df['cluster'] = kmeans.fit_predict(X)
        
        # Etiquetar clusters basándonos en la media de gasto
        centers = df.groupby('cluster')['total_spent'].mean().sort_values().index
        mapping = {centers[0]: "Nuevos/Ocasionales", centers[1]: "Clientes Fieles", centers[2]: "VIP / Big Spenders"}
        df['segment_name'] = df['cluster'].map(mapping)
        
        summary = df['segment_name'].value_counts().to_dict()
        data_points = df[['user_id', 'segment_name', 'total_spent']].to_dict(orient='records')
        
        return {
            "summary": summary,
            "details": data_points,
            "algorithm": "K-Means Clustering"
        }
    except Exception as e:
        print(f"Error Clustering: {str(e)}")
        raise HTTPException(status_code=500, detail=str(e))

# ─────────────────────────────────────────────
#  MÓDULO 3: Previsión de Stock (Risk Analysis)
# ─────────────────────────────────────────────
@app.get("/admin/inventory-risk")
def inventory_risk():
    try:
        # Calcular velocidad de ventas diaria por producto
        query = """
            SELECT i.product_id, p.name, p.stock, SUM(i.quantity) as total_sold,
                   COUNT(DISTINCT DATE(o.created_at)) as days_active
            FROM items i
            JOIN orders o ON i.order_id = o.id
            JOIN products p ON i.product_id = p.id
            WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY i.product_id, p.name, p.stock
        """
        df = pd.read_sql(query, engine)
        
        risks = []
        for _, row in df.iterrows():
            daily_velocity = row['total_sold'] / max(row['days_active'], 1)
            days_left = 999
            if daily_velocity > 0:
                days_left = round(row['stock'] / daily_velocity, 1)
            
            # Si se acaba en menos de 10 días, es un riesgo
            if days_left <= 10 or row['stock'] < 5:
                risks.append({
                    "product_id": int(row['product_id']),
                    "name": row['name'],
                    "stock": int(row['stock']),
                    "days_remaining": days_left,
                    "risk_level": "Alto" if days_left < 3 else "Medio"
                })
        
        return sorted(risks, key=lambda x: x['days_remaining'])
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# ─────────────────────────────────────────────
#  Estadísticas de Admin & Recomendaciones (Existentes)
# ─────────────────────────────────────────────
@app.get("/admin/stats")
async def admin_stats():
    try:
        sales_query = "SELECT DATE(created_at) as date, SUM(total) as daily_sum FROM orders WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY date ORDER BY date ASC"
        sales_df = pd.read_sql(sales_query, engine)
        forecast = []
        if not sales_df.empty and len(sales_df) > 1:
            z = np.polyfit(range(len(sales_df)), sales_df['daily_sum'].values, 1)
            p = np.poly1d(z)
            forecast = [max(0, round(float(v), 2)) for v in p(range(len(sales_df), len(sales_df) + 7))]

        basket_query = "SELECT a.product_id as prod_a, b.product_id as prod_b, count(*) as freq FROM items a JOIN items b ON a.order_id = b.order_id AND a.product_id < b.product_id GROUP BY prod_a, prod_b ORDER BY freq DESC LIMIT 6"
        basket_df = pd.read_sql(basket_query, engine)
        affinity = basket_df.to_dict(orient='records') if not basket_df.empty else []
        p_names = {}
        if affinity:
            all_ids = list(set(basket_df['prod_a'].tolist() + basket_df['prod_b'].tolist()))
            names_df = pd.read_sql(f"SELECT id, name FROM products WHERE id IN ({','.join(map(str, all_ids))})", engine)
            p_names = dict(zip(names_df['id'], names_df['name']))

        return {"sales_forecast": forecast, "product_affinity": affinity, "product_names": p_names}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/recommend/{user_id}")
def recommend(user_id: int, top_k: int = 4):
    try:
        query = "SELECT user_id, product_id, 5 as score FROM ratings UNION SELECT o.user_id, i.product_id, 4 as score FROM orders o JOIN items i ON o.id = i.order_id"
        df = pd.read_sql(query, engine)
        if df.empty: return {"recommended_product_ids": [], "type": "empty"}
        matrix = df.pivot_table(index='user_id', columns='product_id', values='score').fillna(0)
        if user_id not in matrix.index:
            popular = df.groupby('product_id')['score'].count().sort_values(ascending=False).head(top_k)
            ids = [int(x) for x in popular.index.tolist()]
            
            # Monitorización de la predicción (Cold Start)
            log_prediction("Recs", f"User: {user_id} | Type: popular (cold start) | IDs: {ids}")
            
            return {"recommended_product_ids": ids, "type": "popular (cold start)"}
        user_vector = matrix.loc[user_id]
        sims = matrix.corrwith(user_vector, axis=1).sort_values(ascending=False).index[1:6]
        recs = df[df['user_id'].isin(sims) & ~df['product_id'].isin(user_vector[user_vector > 0].index.tolist())]
        ids = recs.groupby('product_id')['score'].sum().sort_values(ascending=False).head(top_k).index.tolist()
        
        # Monitorización de la predicción
        log_prediction("Recs", f"User: {user_id} | Type: personalized | IDs: {ids}")
        
        return {"recommended_product_ids": [int(x) for x in ids], "type": "personalized"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))
