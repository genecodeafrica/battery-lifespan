from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
import joblib
import pandas as pd
import numpy as np
from pathlib import Path


# APPLICATION CONFIGURATION

app = FastAPI(
    title="Lithium-Ion Battery Lifespan Prediction API",
    description="Machine learning API for predicting battery Remaining Useful Life (RUL).",
    version="1.0.0"
)


# MODEL LOCATION

BASE_DIR = Path(__file__).resolve().parent.parent

MODEL_PATH = (
    BASE_DIR
    / "models"
    / "battery_lifespan_model_package.joblib"
)


# LOAD MODEL

try:
    model_package = joblib.load(MODEL_PATH)

    model = model_package["model"]
    FEATURES = model_package["features"]
    EOL_THRESHOLD = model_package["eol_threshold"]
    MODEL_NAME = model_package["model_name"]

except Exception as e:
    model = None
    FEATURES = []
    EOL_THRESHOLD = 0.80
    MODEL_NAME = "Gradient Boosting Regressor"

    print("ERROR: Could not load machine learning model.")
    print(e)


# REQUEST DATA MODEL

class BatteryPredictionRequest(BaseModel):

    cycle: float = Field(..., description="Current battery cycle")

    capacity_mAh: float = Field(
        ...,
        description="Current battery capacity in mAh"
    )

    duration_s: float = Field(
        ...,
        description="Battery test duration in seconds"
    )

    start_voltage_V: float = Field(
        ...,
        description="Starting voltage in volts"
    )

    end_voltage_V: float = Field(
        ...,
        description="Ending voltage in volts"
    )

    avg_temp_C: float = Field(
        ...,
        description="Average battery temperature in Celsius"
    )

    n_samples: float = Field(
        ...,
        description="Number of samples"
    )

    initial_capacity_mAh: float = Field(
        ...,
        description="Initial battery capacity in mAh"
    )

    capacity_retention: float = Field(
        ...,
        description="Current capacity divided by initial capacity"
    )

    capacity_loss_mAh: float = Field(
        ...,
        description="Initial capacity minus current capacity"
    )

    degradation_rate_mAh_per_cycle: float = Field(
        ...,
        description="Battery degradation rate per cycle"
    )


# HEALTH CHECK

@app.get("/")
def root():

    return {
        "status": "online",
        "service": "Lithium-Ion Battery Lifespan Prediction API",
        "model": MODEL_NAME,
        "version": "1.0.0"
    }


@app.get("/health")
def health():

    if model is None:

        return {
            "status": "error",
            "model_loaded": False
        }

    return {
        "status": "healthy",
        "model_loaded": True,
        "model": MODEL_NAME,
        "eol_threshold": EOL_THRESHOLD
    }


# PREDICTION ENDPOINT

@app.post("/predict")
def predict_rul(data: BatteryPredictionRequest):

    if model is None:

        raise HTTPException(
            status_code=500,
            detail="Machine learning model is not loaded."
        )

    try:

        # Convert request to dictionary

        input_data = {
            "cycle": data.cycle,
            "capacity_mAh": data.capacity_mAh,
            "duration_s": data.duration_s,
            "start_voltage_V": data.start_voltage_V,
            "end_voltage_V": data.end_voltage_V,
            "avg_temp_C": data.avg_temp_C,
            "n_samples": data.n_samples,
            "initial_capacity_mAh": data.initial_capacity_mAh,
            "capacity_retention": data.capacity_retention,
            "capacity_loss_mAh": data.capacity_loss_mAh,
            "degradation_rate_mAh_per_cycle":
                data.degradation_rate_mAh_per_cycle
        }

        # Create DataFrame
      
        input_df = pd.DataFrame([input_data])

        # Make sure features are in the exact order
        # expected by the trained model.

        input_df = input_df[FEATURES]

        # Make prediction

        prediction = model.predict(input_df)

        predicted_rul = float(prediction[0])

        # RUL should not be negative
        predicted_rul = max(0, predicted_rul)

        # Calculate estimated EOL cycle

        estimated_eol_cycle = (
            data.cycle + predicted_rul
        )

        
        # Return result
        
        return {
            "success": True,
            "model": MODEL_NAME,
            "current_cycle": data.cycle,
            "predicted_rul_cycles": round(
                predicted_rul,
                2
            ),
            "estimated_eol_cycle": round(
                estimated_eol_cycle,
                2
            ),
            "eol_threshold": EOL_THRESHOLD
        }

    except Exception as e:

        raise HTTPException(
            status_code=500,
            detail=str(e)
        )