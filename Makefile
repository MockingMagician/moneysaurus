tests: phpunit phpcs-check phpstan ## Run tests suite



bench: ## Run benchmarks
	vendor/bin/phpbench run --report='generator: "table", break: ["benchmark", "revs"], cols: ["subject", "mean"]' --bootstrap='vendor/autoload.php' benchmarks



phpunit: ## Launch PHPUnit test suite
	vendor/bin/phpunit --colors=always --coverage-html .coverage -c phpunit.xml

phpunit-junit-log-circle-ci: ## Launch PHPUnit test suite for circle-ci
	vendor/bin/phpunit --colors=always --log-junit tests-results/tests.xml --coverage-xml tests-results/coverage -c phpunit.xml

phpunit-metadata: ## Launch PHPUnit test suite for metadata
	vendor/bin/phpunit --colors=always --log-junit .metadata/tests.xml --coverage-xml .metadata/coverage -c phpunit.xml



phpcs: ## Apply PHP CS fixes
	vendor/bin/php-cs-fixer fix

phpcs-check: ## Coding style checks
	vendor/bin/php-cs-fixer fix --dry-run

phpcs-junit-circle-ci: ## Coding style checks for Circle ci
	vendor/bin/php-cs-fixer fix --dry-run --format=junit | tail -n +2 > tests-results/phpcs/code_style.xml

phpcs-metadata: ## Coding style checks for metadata
	vendor/bin/php-cs-fixer fix --dry-run --format=junit | tail -n +2 > .metadata/code_style.xml



phpstan: ## Static analysis
	vendor/bin/phpstan analyse --level=max src

phpstan-metadata: ## Static analysis pushed to JSON
	vendor/bin/phpstan analyse --level=max --error-format=prettyJson src | tail -n +3 > .metadata/phpstan_result.json && echo "" >> .metadata/phpstan_result.json


metadata-creator: ## Generate metadata
	\
cat .metadata/coverage/index.xml | grep -Pzo '<directory name="/">(?:.|\n\s)*?<lines ([^/]*)' \
| xargs --null | grep -Po 'percent="[^"]*"' \
| xargs --null | grep -Po '[\d]+(\.[\d]+)?' \
| xargs --null -I '{}' echo '{}' > .metadata/.results/coverage.txt \
\ && \
cat .metadata/tests.xml | grep -Pzo '<testsuites>(?:.|\n\s)*?<testsuite([^>]*)' \
| xargs --null | grep -Po 'errors="[^"]*"' \
| xargs --null | grep -Po '[\d]+' \
| xargs --null -I '{}' echo '{}' > .metadata/.results/phpunit.txt \
\


help: ## Display this help message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
