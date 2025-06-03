start:
	docker build -t imdhemy/liap .

composer:
	docker run --rm -v $(PWD):/app -w /app imdhemy/liap composer $(filter-out $@,$(MAKECMDGOALS))

%:
	@:
