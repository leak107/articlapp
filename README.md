Articlapp

- DB use sqlite, `touch database/database.sqlite`
- Mail service used `Mailpit`
- S3 storage used `Amazon  S3`

- Run `artisan migrate:fresh --seed` to populate
- Run `artisan test` to check the functionality of the app
- Run `artisan queue:work` to make sure email service for product transaction will be send to customer email
