<?php

namespace App\Http\Controllers;

class SettingsController extends Controller
{
    /**
     * Display system settings.
     */
    public function index()
    {
        $modelInfo = [
            'name' =>
                'Gradient Boosting Regressor',

            'type' =>
                'Regression',

            'purpose' =>
                'Lithium-Ion Battery Remaining Useful Life Prediction',

            'mae' =>
                669.736465,

            'rmse' =>
                875.951112,

            'r2' =>
                0.857924,

            'estimators' =>
                200,

            'learning_rate' =>
                0.05,

            'max_depth' =>
                2,

            'validation' =>
                '8-Fold Group Cross-Validation',

            'eol_threshold' =>
                '80% Capacity Retention',
        ];

        $systemInfo = [
            'application' =>
                'BatteryLife AI',

            'version' =>
                '1.0.0',

            'framework' =>
                'Laravel 13',

            'frontend' =>
                'Blade + Bootstrap 5',

            'database' =>
                'MySQL',

            'machine_learning' =>
                'Python + Scikit-learn',

            'api' =>
                'FastAPI',

            'prediction_model' =>
                'Gradient Boosting Regressor',
        ];

        return view(
            'settings.index',
            compact(
                'modelInfo',
                'systemInfo'
            )
        );
    }
}