help: ## Shows this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

phpstan: ## run phpstan
	vendor/bin/phpstan -vvv

phpstan-baseline: ## update baseline for phpstan
	vendor/bin/phpstan -vvv analyze -c phpstan.neon --generate-baseline=phpstan.baseline.neon

phpunit: ## run phpunit-unit
	vendor/bin/phpunit

rector: ## run rector
	vendor/bin/rector

cs: ## Run php-cs-fixer
	vendor/bin/php-cs-fixer fix -v

infection: ## run infection
	vendor/bin/infection --threads=max --show-mutations --min-covered-msi=100 --ignore-msi-with-no-mutations

.PHONY: coverage
coverage: ## generate coverage
	XDEBUG_MODE=coverage phpunit --coverage-html coverage

