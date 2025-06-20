.PHONY: build bash start

start: build bash

build:
	docker build -t imdhemy/liap .

bash:
	docker run --rm -it --name liap-container -v $(PWD):/var/www imdhemy/liap bash

%:
	@:
