<?php

namespace App\Services;

use App\Models\Site;
use App\Models\TrialDomain;
use Illuminate\Support\Facades\Auth;

class DomainService
{
    /**
     * Normalize a URL/domain input to a clean domain.
     * 
     * @throws \InvalidArgumentException if input contains path, query, or fragment
     */
    public function normalize(string $input): string
    {
        $input = trim($input);

        if (empty($input)) {
            throw new \InvalidArgumentException('Domain cannot be empty.');
        }

        // Store original for error messages
        $original = $input;

        // Remove protocol if present
        $input = preg_replace('/^https?:\/\//i', '', $input);

        // Remove www prefix
        $input = preg_replace('/^www\./i', '', $input);

        // Remove trailing slash (allowed - just a trailing slash with no path)
        $input = rtrim($input, '/');

        // Check for path, query string, or fragment (after removing trailing slash)
        if (preg_match('/[\/\?#]/', $input)) {
            throw new \InvalidArgumentException(
                "Invalid domain: '{$original}' contains a path, query string, or fragment. Please enter only the domain (e.g., example.com)."
            );
        }

        // Convert to lowercase
        $input = strtolower($input);

        // Remove trailing dots
        $input = rtrim($input, '.');

        // Validate domain format
        if (!$this->isValidDomainFormat($input)) {
            throw new \InvalidArgumentException(
                "Invalid domain format: '{$input}'. Please enter a valid domain (e.g., example.com)."
            );
        }

        return $input;
    }

    /**
     * Check if the normalized domain has valid format.
     */
    public function isValidDomainFormat(string $domain): bool
    {
        // Basic domain validation pattern
        // Must have at least one dot, valid characters, and proper TLD
        $pattern = '/^(?!-)[a-z0-9-]+(\.[a-z0-9-]+)*\.[a-z]{2,}$/';

        return preg_match($pattern, $domain) === 1;
    }

    /**
     * Validate and normalize domain input.
     * Returns normalized domain or throws exception.
     */
    public function validateAndNormalize(string $input): string
    {
        return $this->normalize($input);
    }

    /**
     * Check if domain already exists for the current user.
     */
    public function isDuplicateForUser(string $domain, ?int $userId = null, ?int $excludeSiteId = null): bool
    {
        $userId = $userId ?? Auth::id();

        $query = Site::where('user_id', $userId)
            ->where('domain', $domain);

        if ($excludeSiteId) {
            $query->where('id', '!=', $excludeSiteId);
        }

        return $query->exists();
    }

    /**
     * Check if a free trial has already been used for this domain globally.
     */
    public function hasTrialBeenUsed(string $domain): bool
    {
        return TrialDomain::where('domain', $domain)->exists();
    }

    /**
     * Check if a domain is eligible for a free trial.
     */
    public function isEligibleForTrial(string $domain): bool
    {
        return !$this->hasTrialBeenUsed($domain);
    }

    /**
     * Get the trial record for a domain if it exists.
     */
    public function getTrialRecord(string $domain): ?TrialDomain
    {
        return TrialDomain::where('domain', $domain)->first();
    }

    /**
     * Full validation for adding a new site.
     * Returns array with 'valid' boolean and 'errors' array.
     */
    public function validateNewSite(string $input, ?int $userId = null): array
    {
        $errors = [];
        $normalizedDomain = null;

        try {
            $normalizedDomain = $this->normalize($input);
        } catch (\InvalidArgumentException $e) {
            return [
                'valid' => false,
                'domain' => null,
                'errors' => [$e->getMessage()],
            ];
        }

        // Check for duplicate
        if ($this->isDuplicateForUser($normalizedDomain, $userId)) {
            $errors[] = "You already have a site registered for '{$normalizedDomain}'.";
        }

        return [
            'valid' => empty($errors),
            'domain' => $normalizedDomain,
            'errors' => $errors,
            'trial_eligible' => $this->isEligibleForTrial($normalizedDomain),
        ];
    }
}
