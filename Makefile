.PHONY: all

SHELL=/bin/bash -e

.DEFAULT_GOAL := help

help: ## This help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

phpunit:  ## Run phpUnit tests
	@docker run --init -it --rm -v "$$(pwd):/project" -v "$$(pwd)/tmp-phpqa:/tmp" -e "php7-xdebug php7-tokenizer" -w /project jakzal/phpqa:php7.4-alpine phpdbg -qrr ./vendor/bin/phpunit

infection:  ## Run phpUnit tests
	docker run --init -it --rm -v "$$(pwd):/project" -v "$$(pwd)/tmp-phpqa:/tmp" -e "php7-xdebug php7-tokenizer" -w /project jakzal/phpqa:php7.4-alpine /tools/infection run --initial-tests-php-options='-dpcov.enabled=1'

dump-autoload:  ## Dumps composer autoload files
	@docker run --rm --interactive --tty   --volume $$PWD:/app   --user $$(id -u):$$(id -g)   composer dump-autoload