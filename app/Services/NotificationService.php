<?php

namespace App\Services;

use App\Models\Battery;
use App\Models\Notification;
use App\Models\Prediction;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public function create(
        User $user,
        string $type,
        string $title,
        string $message,
        string $severity = 'info',
        ?Battery $battery = null,
        ?Prediction $prediction = null
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'battery_id' => $battery?->id,
            'prediction_id' => $prediction?->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'severity' => $severity,
            'is_read' => false,
        ]);
    }

    /**
     * Create a notification when an AI prediction is generated.
     */
    public function predictionGenerated(
        User $user,
        Battery $battery,
        Prediction $prediction
    ): void {
        $rul = round(
            (float) $prediction->predicted_rul_cycles
        );

        $this->create(
            $user,
            'new_prediction',
            'New AI Prediction Generated',
            'A new battery lifespan prediction has been generated for '
            . $battery->battery_code
            . '. Predicted remaining useful life is approximately '
            . number_format($rul)
            . ' cycles.',
            'success',
            $battery,
            $prediction
        );

        $this->evaluateBatteryAlerts(
            $user,
            $battery,
            $prediction
        );
    }

    /**
     * Evaluate battery health and operational alerts.
     */
    public function evaluateBatteryAlerts(
        User $user,
        Battery $battery,
        ?Prediction $prediction = null
    ): void {
        $measurement = $battery->measurements()
            ->latest('cycle')
            ->first();

        if (!$measurement) {
            return;
        }

        /*
         * Battery Health Alert
         */
        $health = null;

        if ($measurement->capacity_retention !== null) {
            $health =
                (float) $measurement->capacity_retention * 100;
        }

        if ($health !== null) {
            if ($health < 80) {
                $this->createUniqueAlert(
                    $user,
                    $battery,
                    'critical_health',
                    'Critical Battery Health',
                    'Battery '
                    . $battery->battery_code
                    . ' has reached '
                    . number_format($health, 2)
                    . '% capacity retention, which is below the 80% EOL threshold.',
                    'danger',
                    $prediction
                );
            } elseif ($health < 90) {
                $this->createUniqueAlert(
                    $user,
                    $battery,
                    'warning_health',
                    'Battery Health Warning',
                    'Battery '
                    . $battery->battery_code
                    . ' is currently at '
                    . number_format($health, 2)
                    . '% capacity retention.',
                    'warning',
                    $prediction
                );
            }
        }

        /*
         * Low RUL Alert
         */
        if ($prediction) {
            $rul = (float) $prediction->predicted_rul_cycles;

            if ($rul <= 500) {
                $this->createUniqueAlert(
                    $user,
                    $battery,
                    'low_rul',
                    'Low Remaining Useful Life',
                    'Battery '
                    . $battery->battery_code
                    . ' has an estimated remaining useful life of approximately '
                    . number_format(round($rul))
                    . ' cycles.',
                    'danger',
                    $prediction
                );
            }
        }

        /*
         * High Temperature Alert
         */
        if ($measurement->avg_temp_C !== null) {
            $temperature =
                (float) $measurement->avg_temp_C;

            if ($temperature >= 45) {
                $this->createUniqueAlert(
                    $user,
                    $battery,
                    'high_temperature',
                    'High Battery Temperature',
                    'Battery '
                    . $battery->battery_code
                    . ' recorded an average temperature of '
                    . number_format($temperature, 2)
                    . ' °C.',
                    'warning',
                    $prediction
                );
            }
        }

        /*
         * Rapid Capacity Degradation Alert
         */
        if (
            $measurement->degradation_rate_mAh_per_cycle !== null
            &&
            (float) $measurement->degradation_rate_mAh_per_cycle <= -0.05
        ) {
            $rate =
                (float) $measurement->degradation_rate_mAh_per_cycle;

            $this->createUniqueAlert(
                $user,
                $battery,
                'rapid_degradation',
                'Rapid Capacity Degradation',
                'Battery '
                . $battery->battery_code
                . ' is showing a capacity degradation rate of '
                . number_format($rate, 5)
                . ' mAh per cycle.',
                'warning',
                $prediction
            );
        }
    }

    /**
     * Prevent duplicate alerts for the same battery and alert type.
     */
    private function createUniqueAlert(
        User $user,
        Battery $battery,
        string $type,
        string $title,
        string $message,
        string $severity,
        ?Prediction $prediction = null
    ): void {
        $existing = Notification::where('user_id', $user->id)
            ->where('battery_id', $battery->id)
            ->where('type', $type)
            ->where('is_read', false)
            ->exists();

        if ($existing) {
            return;
        }

        $this->create(
            $user,
            $type,
            $title,
            $message,
            $severity,
            $battery,
            $prediction
        );
    }
}