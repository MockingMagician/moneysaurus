tests: phpunit phpcs-check phpstan ## Run tests suite

bench: ## Run benchmarks
	vendor/bin/phpbench run --report='generator: "table", break: ["benchmark", "revs"], cols: ["subject", "mean"]' --bootstrap='vendor/autoload.php' benchmarks

phpunit: ## Launch PHPUnit test suite
	vendor/bin/phpunit --colors=always --coverage-html .coverage -c phpunit.xml

phpcs: ## Apply PHP CS fixes
	vendor/bin/php-cs-fixer fix

phpcs-check: ## Coding style checks
	vendor/bin/php-cs-fixer fix --dry-run

phpstan: ## Static analysis
	vendor/bin/phpstan analyse --level=max src

phpunit-junit-log-circle-ci: ##  Launch PHPUnit test suite with --log-junit and --coverage-xml for circle-ci
	vendor/bin/phpunit --colors=always --log-junit tests-results/tests.xml --coverage-xml tests-results/coverage -c phpunit.xml

help: ## Display this help message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
