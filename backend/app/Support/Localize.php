<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Localize
{
    public static function apply(Model $model, ?string $locale): array
    {
        $row = $model->toArray();
        $translations = $row['translations'] ?? [];
        if (is_string($translations)) {
            $translations = json_decode($translations, true) ?: [];
        }
        $map = [];
        if (is_array($translations)) {
            if ($locale && isset($translations[$locale]) && is_array($translations[$locale])) {
                $map = $translations[$locale];
            } elseif ($locale && $locale !== 'vi' && isset($translations['en']) && is_array($translations['en'])) {
                // Fallback to English so non-Vietnamese UI never shows raw Vietnamese clinical data.
                $map = $translations['en'];
            }
        }
        foreach ($map as $k => $v) {
            if (array_key_exists($k, $row) && $v !== null && $v !== '') {
                $row[$k] = $v;
            }
        }
        unset($row['translations']);

        return $row;
    }

    public static function collection(Collection $items, ?string $locale): array
    {
        return $items->map(fn (Model $m) => self::apply($m, $locale))->values()->all();
    }
}
