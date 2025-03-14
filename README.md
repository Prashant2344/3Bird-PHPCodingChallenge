# Run without docker
1. Install PHP 8.1 or higher version
2. Install latest composer from https://getcomposer.org/
3. Rename .env.example to .env or create new .env file and copy content from .env.example
4. Run composer install
5. Generate Application Key php artisan key:generate
6. Run php artisan serve
7. Visit http://127.0.0.1:8000/sections/books

# Run with docker
1. Setup Docker & Docker Compose on your local computer
2. Run docker-compose up --build
3. Navigate to project inside docker via:  docker exec -it container_id bash
4. Run composer install
5. Run php artisan key:generate
6. http://127.0.0.1:8080/sections/splifeandstylert

# Following are the task details

# Added ValidateSectionMiddleware to ensure section names are in lowercase letters and hyphens, returning a 400 error for invalid formats 

# Added generateRssFeed functional in RssFeedService to ensure HTTPS requests to the remote API are in JSON format, with XML conversion handled on the server application.

# Ensured the resulting RSS feed complies with W3C standards and can be tested at https://validator.w3.org/feed/#validate_by_input

# Implemented Laravel Cache Facade to cache API responses for 10 minutes, serving cached data if valid, or fetching fresh data from the remote API when the cache expires.

# The two test cases, testGetArticlesReturnsValidRss and testGetArticlesHandlesApiFailure, have been added to ensure that the getArticles method correctly handles both successful API responses and failure scenarios 
# Run php artisan test to run testcase.

# Monolog has been used for logging, and all the logs can be found inside \storage\logs\

# PHP_CodeSniffer has been used for linting. To run linting, use the following command: vendor/bin/phpcs app/ or vendor/bin/phpcs app/ > phpcs-output.txt.
# replace app/ with the path of any file or folder to scan those files or folders