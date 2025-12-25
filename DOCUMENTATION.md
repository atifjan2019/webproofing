# SiteProof - Laravel 10 Website Monitoring Application

## Project Documentation

**Created:** December 24, 2025  
**Laravel Version:** 10.x  
**PHP Version:** 8.4.10  
**Database:** MySQL (via XAMPP)

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Installation & Setup](#installation--setup)
3. [Database Structure](#database-structure)
4. [Core Features Implemented](#core-features-implemented)
5. [File Structure](#file-structure)
6. [Routes](#routes)
7. [Services](#services)
8. [Access Control](#access-control)
9. [Views & UI](#views--ui)
10. [Future Development](#future-development)

---

## Project Overview

SiteProof is a multi-site management application that allows users to:
- Manage multiple websites per account
- Take and store website screenshots
- Connect Google Analytics 4 and Search Console
- Track 7-day free trials per domain (globally enforced)

### Key Constraints (Phase 1)
- No Stripe/billing integration yet
- No real Playwright screenshot capture yet
- No real Google OAuth yet (simulated)
- No VPS or Cloudflare integration yet

---

## Installation & Setup

### Prerequisites
- XAMPP with MySQL running
- PHP 8.1+
- Composer
- Node.js & npm

### Steps Performed

```bash
# 1. Create Laravel 10 project
composer create-project laravel/laravel . "10.*"

# 2. Configure .env for MySQL
# DB_DATABASE=siteproof
# DB_USERNAME=root
# DB_PASSWORD=

# 3. Install Laravel Breeze (Blade + Tailwind)
composer require laravel/breeze --dev
php artisan breeze:install blade

# 4. Run migrations
php artisan migrate

# 5. Install npm dependencies and build
npm install
npm run dev

# 6. Start development server
php artisan serve
```

### Test User Created
- **Email:** admin@gmail.com
- **Password:** 123

---

## Database Structure

### Tables Created

#### 1. `users` (Laravel default)
Standard Breeze authentication table.

#### 2. `sites`
```php
Schema::create('sites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('raw_url');           // Original URL input
    $table->string('domain')->index();   // Normalized domain
    $table->string('name')->nullable();  // Display name
    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
    $table->timestamps();
    $table->index(['user_id', 'domain']);
});
```

#### 3. `trial_domains`
```php
Schema::create('trial_domains', function (Blueprint $table) {
    $table->id();
    $table->string('domain')->unique();  // One trial per domain globally
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('site_id')->constrained()->onDelete('cascade');
    $table->timestamp('trial_started_at')->nullable();
    $table->timestamp('trial_ends_at')->nullable();  // 7 days from start
    $table->boolean('is_expired')->default(false);
    $table->timestamps();
    $table->index('domain');
    $table->index('trial_ends_at');
});
```

#### 4. `site_screenshots`
```php
Schema::create('site_screenshots', function (Blueprint $table) {
    $table->id();
    $table->foreignId('site_id')->constrained()->onDelete('cascade');
    $table->string('url');
    $table->string('image_url')->nullable();   // Placeholder URL
    $table->string('image_path')->nullable();  // Storage path
    $table->enum('device_type', ['desktop', 'mobile', 'tablet'])->default('desktop');
    $table->integer('width')->nullable();
    $table->integer('height')->nullable();
    $table->string('status')->default('pending');  // pending, captured, failed
    $table->text('error_message')->nullable();
    $table->timestamp('captured_at')->nullable();
    $table->timestamps();
    $table->index(['site_id', 'url']);
    $table->index('status');
});
```

#### 5. `google_accounts`
```php
Schema::create('google_accounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
    $table->string('google_id')->nullable();
    $table->string('email')->nullable();
    $table->string('name')->nullable();
    $table->text('access_token')->nullable();
    $table->text('refresh_token')->nullable();
    $table->timestamp('token_expires_at')->nullable();
    $table->json('scopes')->nullable();
    $table->timestamps();
    $table->index('google_id');
});
```

#### 6. `site_google_properties`
```php
Schema::create('site_google_properties', function (Blueprint $table) {
    $table->id();
    $table->foreignId('site_id')->constrained()->onDelete('cascade');
    $table->string('ga4_property_id')->nullable();
    $table->string('ga4_property_name')->nullable();
    $table->boolean('ga4_connected')->default(false);
    $table->boolean('gsc_connected')->default(false);
    $table->string('gsc_domain')->nullable();  // Domain property only (sc-domain:example.com)
    $table->timestamps();
    $table->unique('site_id');
    $table->index('ga4_property_id');
});
```

> **Note:** Phase 2 Prompt A removed URL prefix support. Only domain properties (`sc-domain:example.com`) are now supported for GSC.

### Model Relationships

```
User
├── hasMany Sites
├── hasMany TrialDomains
└── hasOne GoogleAccount

Site
├── belongsTo User
├── hasOne TrialDomain
├── hasMany SiteScreenshots
└── hasOne SiteGoogleProperty
```

---

## Core Features Implemented

### 1. Domain Normalization (Prompt 3)

**Location:** `app/Services/DomainService.php`

**Rules:**
- Strips `http://`, `https://`, `www.`
- Removes trailing slashes
- Converts to lowercase
- Rejects paths, query strings, fragments
- Validates domain format

**Example:**
```
Input:  https://www.Example.com/
Output: example.com
```

**Validation Rules Created:**
- `app/Rules/ValidDomain.php` - Validates and normalizes domain
- `app/Rules/UniqueDomainForUser.php` - Prevents duplicate domains per user

### 2. Trial System (Prompt 4)

**Location:** `app/Services/TrialService.php`

**Behavior:**
- 7-day trial starts automatically when domain is first added
- Trial is **globally locked per domain** (one trial per domain ever)
- If domain already used trial, new sites are created as "paused"
- Trial data is never deleted after expiration
- `getSiteStatus()` returns: `trial`, `expired`, `paused`, or `unknown`

**Key Methods:**
```php
startTrial(Site $site)           // Start 7-day trial
hasTrialBeenUsed(string $domain) // Check if trial used globally
isTrialActive(TrialDomain $trial)// Check if trial still active
getRemainingDays(TrialDomain)    // Get days left
getSiteStatus(Site $site)        // Get full status array
canMonitor(Site $site)           // Boolean check for actions
```

### 3. Site Management (Prompt 5)

**Controller:** `app/Http/Controllers/SiteController.php`

**Features:**
- List all sites with trial status
- Add new website (domain only, with validation)
- Site overview page with trial countdown
- Delete site functionality

### 4. Screenshot Gallery (Prompt 6)

**Controller:** `app/Http/Controllers/ScreenshotController.php`

**Features:**
- Grid gallery with pagination
- "Take First Screenshot" button
- Creates placeholder screenshot records with:
  - Timestamp
  - Success status
  - Placeholder image URL (via.placeholder.com)
- Ready for real screenshot service integration

### 5. Google Connect UI (Phase 2 Prompt B: Real OAuth)

**Controller:** `app/Http/Controllers/GoogleConnectController.php`
**Service:** `app/Services/GoogleService.php`

**Features:**
- **Real Google OAuth** integration (Socialite + Google API Client)
- Encrypted token storage in `google_accounts` table
- **Secure Token Management:**
  - Access tokens encrypted
  - Refresh tokens encrypted
  - Auto-refresh on expiration
- **Property Fetching:**
  - Fetches real GA4 properties from Google Analytics Admin API
  - Fetches real GSC properties from Search Console API
  - Filters for GSC **domain properties** only
- **Access Control:**
  - Verifies user has access to selected properties before saving
- **UI Updates:**
  - Shows connected Google account email
  - Connect/Disconnect flow
  - Error handling for permission issues

### 6. Access Control (Prompt 8)

**Policy:** `app/Policies/SitePolicy.php`
**Middleware:** `app/Http/Middleware/CheckSiteAccess.php`

**Rules:**
- Users can only access their own sites
- Paused sites are read-only (write actions blocked)
- Trial-expired sites show upgrade prompts
- All controllers use `$this->authorize()` checks

### 7. Dashboard (Prompt 9)

**Controller:** `app/Http/Controllers/DashboardController.php`

**Features:**
- Stats cards: Total, Active, On Trial, Need Upgrade
- Expiring soon alerts (trials within 3 days)
- Recent sites list with status badges
- Quick actions (Add Website, View All)
- Placeholder sections for future analytics and screenshot stats

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── GoogleConnectController.php
│   │   ├── ScreenshotController.php
│   │   └── SiteController.php
│   └── Middleware/
│       └── CheckSiteAccess.php
├── Models/
│   ├── GoogleAccount.php
│   ├── Site.php
│   ├── SiteGoogleProperty.php
│   ├── SiteScreenshot.php
│   ├── TrialDomain.php
│   └── User.php
├── Policies/
│   └── SitePolicy.php
├── Rules/
│   ├── UniqueDomainForUser.php
│   └── ValidDomain.php
└── Services/
    ├── DomainService.php
    └── TrialService.php

resources/views/
├── dashboard.blade.php
└── sites/
    ├── create.blade.php
    ├── google-configure.blade.php
    ├── google-connect.blade.php
    ├── index.blade.php
    ├── screenshots.blade.php
    └── show.blade.php

database/migrations/
├── 2025_12_24_134417_create_sites_table.php
├── 2025_12_24_134446_create_trial_domains_table.php
├── 2025_12_24_134446_create_site_screenshots_table.php
├── 2025_12_24_134446_create_google_accounts_table.php
└── 2025_12_24_134447_create_site_google_properties_table.php
```

---

## Routes

```php
// Dashboard
GET  /dashboard                     DashboardController@index

// Sites
GET  /sites                         SiteController@index
GET  /sites/create                  SiteController@create
POST /sites                         SiteController@store
GET  /sites/{site}                  SiteController@show
DELETE /sites/{site}                SiteController@destroy

// Screenshots
GET  /sites/{site}/screenshots      ScreenshotController@index
POST /sites/{site}/screenshots      ScreenshotController@capture
DELETE /sites/{site}/screenshots/{screenshot}  ScreenshotController@destroy

// Google Connect
GET  /sites/{site}/google           GoogleConnectController@show
POST /sites/{site}/google/connect   GoogleConnectController@connect
GET  /sites/{site}/google/configure GoogleConnectController@configure
POST /sites/{site}/google/configure GoogleConnectController@store
DELETE /sites/{site}/google         GoogleConnectController@disconnect
```

---

## Services

### DomainService

```php
normalize(string $input): string
isValidDomainFormat(string $domain): bool
isDuplicateForUser(string $domain, ?int $userId, ?int $excludeSiteId): bool
hasTrialBeenUsed(string $domain): bool
isEligibleForTrial(string $domain): bool
validateNewSite(string $input, ?int $userId): array
```

### TrialService

```php
startTrial(Site $site): ?TrialDomain
hasTrialBeenUsed(string $domain): bool
isEligibleForTrial(string $domain): bool
getTrialForDomain(string $domain): ?TrialDomain
isTrialActive(TrialDomain $trial): bool
getRemainingDays(TrialDomain $trial): int
markExpiredTrials(): int
getSiteStatus(Site $site): array
canMonitor(Site $site): bool
```

---

## Access Control

### Middleware Registration

In `app/Http/Kernel.php`:
```php
'site.access' => \App\Http\Middleware\CheckSiteAccess::class,
```

### Policy Methods

```php
viewAny(User $user): bool    // Anyone can list their sites
view(User $user, Site $site): bool    // Owner only
create(User $user): bool     // Anyone can create
update(User $user, Site $site): bool  // Owner only
delete(User $user, Site $site): bool  // Owner only
```

---

## Views & UI

### Design System
- **Framework:** Tailwind CSS (via Breeze)
- **Font:** Figtree (Bunny Fonts)
- **Icons:** Heroicons (inline SVG)
- **Layout:** Responsive grid with cards

### UI Components
- Stats cards with icons
- Trial progress bars
- Status badges (trial, expired, paused)
- Upgrade banners (gradient amber/orange)
- Empty states with CTAs
- Flash message alerts (success/error)

### Color Scheme
- **Primary:** Indigo-600
- **Success/Active:** Green
- **Warning/Trial:** Blue
- **Danger/Expired:** Red/Amber
- **Neutral:** Gray scale

---

## Future Development

### Phase 2 - Not Yet Implemented
- [ ] Real Playwright screenshot capture
- [ ] Real Google OAuth integration
- [ ] Stripe subscription/billing
- [ ] VPS deployment (Hetzner)
- [ ] Cloudflare integration
- [ ] Real analytics data from GA4/GSC
- [ ] Automated screenshot scheduling
- [ ] Email notifications for trial expiration
- [ ] Admin panel for managing all users

### Database Ready For
- OAuth tokens storage (google_accounts)
- Screenshot file paths (site_screenshots.image_path)
- GA4/GSC property IDs (site_google_properties)

---

## Running the Application

```bash
# Terminal 1: Start Laravel server
cd /Users/atifjan/Desktop/webproofing
php artisan serve

# Terminal 2: Start Vite dev server (for hot reload)
npm run dev

# Access the application
http://127.0.0.1:8000

# Login credentials
Email: admin@gmail.com
Password: 123
```

---

## Summary

This Phase 1 implementation provides a solid foundation with:

1. **Clean Architecture** - Services, Policies, Middleware separation
2. **Proper Data Modeling** - Relationships and indexing optimized
3. **Domain Validation** - Robust normalization and duplicate prevention
4. **Trial System** - Global per-domain enforcement
5. **Modern UI** - Responsive Tailwind design with status indicators
6. **Access Control** - Policy-based authorization
7. **Extensible Structure** - Ready for real integrations in Phase 2

All features have been tested and are working as expected.
