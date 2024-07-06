# Planète PHP

Source code for [planete-php.fr](https://planete-php.fr).

## Dev setup

You need to have [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) installed on your machine.

Then follow these steps:

1. Clone the repository
2. Run `make docker-start`
3. Run `make install`
4. Open your browser at [https://localhost:8443](https://localhost:8443)

You can stop the containers with `make docker-stop`.

## Code quality

You can run the following commands to check the code quality:

- `make qa-test` to run tests
- `make qa-phpstan` to run PHPStan
- `make qa-cs-fix` to run PHP-CS-Fixer

## Contributing

Feel free to contribute to this project by opening issues or pull requests.
