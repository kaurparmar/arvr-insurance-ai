# ARVR Insurance

A Laravel 12 insurance portal built with MongoDB, multi-language localization, and a VR-themed frontend experience.

## Project Overview

ARVR Insurance is a MongoDB-backed Laravel application that delivers:

- Authentication and user profile management
- Insurance plan browsing and detailed plan pages
- Policy application and approval workflows
- Claims filing and claim status tracking
- Transaction checkout for policy payments
- Localization for English, Hindi, and Punjabi
- A VR-style demo page at /vr

## Functional Areas

### User Authentication

- Login and logout
- Registration
- Email verification
- Password reset and confirmation
- Profile edit and account management

### Insurance and Policy Workflows

- Browse available plans on /plans
- View plan details on /plans/{plan}
- Apply for a policy on /policies/apply/{plan}
- Confirm policy application success
- Pay for approved policies on /transactions/{policy}/create

### Claims Management

- View existing claims on /claims
- Submit a new claim on /claims/create
- Inspect claim details on /claims/{id}

### VR Experience

- Explore the VR-themed frontend at /vr
- The page uses the same localization system as other pages

## Localization

Localization is implemented in:

- resources/lang/en/messages.php
- resources/lang/hi/messages.php
- resources/lang/pa/messages.php

Blade templates use the __() helper to render translated text.
Main localized content includes:

- page headings
- button labels
- section titles
- workflow step labels
- status and summary text

Locale switching is supported by /lang/{locale}.

## Architecture and Data Flow

### High-level flow

1. A browser requests a route such as /plans, /claims, or /policies/apply/{plan}.
2. Laravel routes in routes/web.php and routes/auth.php forward the request to controllers.
3. Controllers load models backed by MongoDB.
4. Blade templates render the page with translated copy.
5. User form submissions are handled by controllers and saved to MongoDB.

### Data flow diagram

[Browser] --> [Laravel Route] --> [Controller] --> [MongoDB Model]
      ^                                       |
      |                                       v
      +--------------- [Blade View] <---------+

### Example sequence for plan application

- User opens /plans
- Controller retrieves plan data
- User clicks apply and reaches /policies/apply/{plan}
- The application form is displayed with localized text
- On submit, a new policy is saved and the user is redirected to success or payment

## Installation

1. Clone the repository:

git clone <repository-url>
cd arvr-insurance

2. Install PHP dependencies:

composer install

3. Install Node dependencies:

npm install

4. Copy the environment file:

cp .env.example .env

5. Generate the application key:

php artisan key:generate

6. Configure MongoDB in .env:

DB_CONNECTION=mongodb
MONGODB_URI="your-mongodb-uri"
MONGODB_DATABASE=your_database_name

7. Start the development server:

php artisan serve

## Local Development Commands

- php artisan serve to start the app server
- npm run dev to compile frontend assets in development mode
- npm run build to compile production assets
- php artisan view:clear to clear compiled views
- php artisan config:clear to clear cached configuration

## Testing

Run tests with:

php artisan test

### Test environment details

- The application uses MongoDB in .env
- phpunit.xml should set DB_CONNECTION=mongodb
- MONGODB_DATABASE should be arvr-insurance_test for tests

### If tests fail with SQLite errors

If you see the error:

Illuminate\Database\SQLiteConnection::getCollection does not exist

then the test runtime is still using SQLite instead of MongoDB.

Verify phpunit.xml or env.testing is configured for MongoDB.

## Important GitHub Notes

- Do not commit .env
- Commit composer.lock and package-lock.json
- Ensure vendor/, node_modules/, and public/build are excluded by .gitignore
- Document MongoDB setup clearly for reviewers
- Keep phpunit.xml as the canonical test configuration

## Useful Routes

- / home page
- /about about page
- /calculator calculator page
- /claims claims dashboard
- /claims/create submit a claim
- /plans insurance plans
- /plans/{plan} plan detail page
- /policies/apply/{plan} policy application
- /transactions/{policy}/create payment checkout
- /lang/{locale} switch language
- /vr VR demo page

## License

This project is licensed under the MIT License.
