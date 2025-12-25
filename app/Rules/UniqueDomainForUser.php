<?php

namespace App\Rules;

use App\Services\DomainService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueDomainForUser implements ValidationRule
{
    protected ?int $userId;
    protected ?int $excludeSiteId;

    public function __construct(?int $userId = null, ?int $excludeSiteId = null)
    {
        $this->userId = $userId;
        $this->excludeSiteId = $excludeSiteId;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domainService = app(DomainService::class);

        try {
            $normalizedDomain = $domainService->normalize($value);

            if ($domainService->isDuplicateForUser($normalizedDomain, $this->userId, $this->excludeSiteId)) {
                $fail("You already have a site registered for '{$normalizedDomain}'.");
            }
        } catch (\InvalidArgumentException $e) {
            // Domain validation failed, let ValidDomain rule handle this
        }
    }
}
