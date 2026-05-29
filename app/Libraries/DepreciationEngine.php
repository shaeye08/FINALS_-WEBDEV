<?php

namespace App\Libraries;

use DateTime;

class DepreciationEngine
{
    /**
     * Compute Straight-Line Fiscal Depreciation
     */
    public static function calculateStraightLine(float $cost, float $salvage, int $lifeYears, ?string $purchaseDate): array
    {
        if ($lifeYears <= 0) return ['current_value' => $salvage, 'depreciated_amount' => 0];
        if (empty($purchaseDate)) return ['current_value' => $cost, 'depreciated_amount' => 0];

        $purchase = new DateTime($purchaseDate);
        $current  = new DateTime();
        $interval = $purchase->diff($current);
        
        // Calculate age in accurate fractional float years
        $ageInYears = $interval->y + ($interval->m / 12) + ($interval->d / 365);
        $ageInYears = min($ageInYears, $lifeYears);

        $annualDepreciation = ($cost - $salvage) / $lifeYears;
        $totalDepreciated   = $annualDepreciation * $ageInYears;
        $currentValue       = max($cost - $totalDepreciated, $salvage);

        return [
            'current_value'      => round($currentValue, 2),
            'depreciated_amount' => round($totalDepreciated, 2)
        ];
    }

    /**
     * Compute Double-Declining Balance Fiscal Depreciation (Accelerated Model)
     */
    public static function calculateDoubleDeclining(float $cost, float $salvage, int $lifeYears, ?string $purchaseDate): array
    {
        if ($lifeYears <= 0) return ['current_value' => $salvage, 'depreciated_amount' => 0];
        if (empty($purchaseDate)) return ['current_value' => $cost, 'depreciated_amount' => 0];

        $purchase = new DateTime($purchaseDate);
        $current  = new DateTime();
        $yearsElapsed = max(0, $purchase->diff($current)->y); // Whole years for declining balance step evaluation

        $rate = (1 / $lifeYears) * 2;
        $currentValue = $cost;

        // Iterate through compounding years
        for ($i = 0; $i < min($yearsElapsed, $lifeYears); $i++) {
            $depreciationForYear = $currentValue * $rate;
            if (($currentValue - $depreciationForYear) < $salvage) {
                $currentValue = $salvage;
                break;
            }
            $currentValue -= $depreciationForYear;
        }

        return [
            'current_value'      => round($currentValue, 2),
            'depreciated_amount' => round($cost - $currentValue, 2)
        ];
    }
}