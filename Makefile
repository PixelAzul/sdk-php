VERSION	   = 0.1.13
SHELL		:= $(shell which bash)
.SHELLFLAGS = -c

.SILENT: ;
.ONESHELL: ;
.NOTPARALLEL: ;
.EXPORT_ALL_VARIABLES: ;
default: help-default;
Makefile: ;

help-default help:
	@echo "======================================================="
	@echo "					Options"
	@echo "======================================================="
	@echo "          test: Execute TDD tests"
	@echo "         setup: Configure the project in this machine"
	@echo ""

test:
	vendor/bin/phpunit --configuration tests/phpunit.xml

setup:
	cpz install --dev --prefer-dist