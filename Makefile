up:
	docker-compose up -d
down:
	docker-compose down
test:
	docker exec -it php-container php vendor/bin/codecept run