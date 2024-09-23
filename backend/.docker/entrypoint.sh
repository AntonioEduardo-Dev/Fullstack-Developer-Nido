rm .env
cp .env.example .env

composer install

php artisan key:generate

php artisan jwt:secret

sleep 10

php artisan migrate

php artisan db:seed

php artisan l5-swagger:generate

php artisan importar:chunkpalavras https://raw.githubusercontent.com/meetDeveloper/freeDictionaryAPI/refs/heads/master/meta/wordList/english.txt

php artisan serve --host=0.0.0.0 --port=9000