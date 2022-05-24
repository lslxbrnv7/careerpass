# Careerpass Network Developer Tech Test

# Scenario
A developer has created a simple and basic JSON API system for job listings with CRUD functionality in Laravel.
The project has _not_ been reviewed by a team member and has been deployed into production.

A job listing is generally linked to a company entity. This system is missing the ability to control data at a company level.

# Your task
* Add and extend the system to include a simple Company API
    * Extract Company structure/data from the job table to a new company table
    * Add `is_active` to a company table that brings down all jobs listed for that company
    * Create a basic resource JSON API for company endpoints
* Investigate and look through the code and find some bugs. (_Hint_: Some are not highlighted by tests).
* Create and implement solutions to fix those bugs.
* Review the code, structure and tests. Write some thoughts on the general state of these 3 areas and improvements you would make into a document.

# Submission
Please send over an archive with the following:
* Your updated repo with your changes from the task including the solutions to the bugs you found
* Your review and improvement document

# Key Folders
```
|── app
|   |── http
|       |── controllers
|   |── models
|   |── observers
|   |── tasks
|
|── database
|   |── factories
|   |── migrations
```
# Requirements
* PHP8
* Composer
* Docker
* Port 80 on localhost

# Setup Instructions

1. Clone the repository `git clone https://gitlab.com/careerpass-network-public/developer-tech-test.git`
2. Install dependencies `composer install`
3. Launch Sail `vendor/bin/sail up -d`
4. Shell into Sail `vendor/bin/sail shell`
5. Bring up the database `composer reset-db`

# Commands
* Optionally load in some test jobs `php artisan factory:load-jobs`
* To run tests `composer test`
* To reset database `composer reset-db`

# Useful info
* JSON API Example URL `http://localhost/api/jobs`
* Authenticate with the JSON API through bearer auth token `1|L2uIeTHjPFfE2kkg8zuGFwdAximpdI6nCLaRU9Hz`
