<?php

namespace App\Rules;

use App\Services\DomainService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDomain implements ValidationRule
{
    protected ?string $normalizedDomain = null;

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domainService = app(DomainService::class);

        try {
            $this->normalizedDomain = $domainService->normalize($value);
        } catch (\InvalidArgumentException $e) {
            $fail($e->getMessage());
        }
    }

    /**
     * Get the normalized domain after validation.
     */
    public function getNormalizedDomain(): ?string
    {
        return $this->normalizedDomain;
    }
}
