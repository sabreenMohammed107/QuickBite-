<?php

namespace App\Traits;

trait HasTranslations
{
    public function t(string $field, ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $value  = $this->getAttribute($field);

        if (\is_array($value)) {
            return (string) ($value[$locale] ?? $value['en'] ?? $value['ar'] ?? '');
        }

        return (string) ($value ?? '');
    }
}
