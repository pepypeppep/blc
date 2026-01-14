# LMS

## Seeding Data

```sh
php artisan migrate:fresh --seed
php artisan unorjenis:sync
php artisan unor:sync
php artisan instansi:sync
php artisan user:sync
```

## Fill Following Data on Config Table

| Key                  | Value                        |
| -------------------- | ---------------------------- |
| bantara_url          | https://bantara.inidev.my.id |
| bantara_key          | {bantara_api_key}            |
| bantara_callback_key | {bantara_callback_key}       |

# Background Jobs

Using crontab run this script every 10 minutes

```sh
php artisan certificate:collect
```
