<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStatus implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedStatuses = ['draft', 'published', 'archived'];

        if (!in_array($value, $allowedStatuses)) {
            $fail("The selected {$attribute} is invalid. Allowed values are: " . implode(', ', $allowedStatuses));
        }
    }
}
