up:
	docker compose -f .docker/docker-compose.yml up -d --build
down:
	docker compose down
# === Composer install ===
ci:
	docker exec axiom_php composer install --no-interaction --prefer-dist
# === Composer reset database ===
init: up ci
	@echo "Project initialized successfully."
# === Утилиты ===
bash:
	docker exec -it this_php bash
