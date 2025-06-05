.PHONY: build bash

start: build bash

build:
	docker build -t imdhemy/liap .

bash:
	docker run --rm -it -v $(PWD):/var/www imdhemy/liap bash

%:
	@:
