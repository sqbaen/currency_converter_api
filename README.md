
# CURRENCY CONVERTER - API

Technology: PHP (Laravel) + MySQL


## Features

- Supports convertion only from Singaporean Dollars (SGD) to Polish zloty (PLN).
- Seeder: creates exchange rates for today and 13 days before.
- Exchange rates are fetched from Database.


## API Reference

#### Convert an *amount* *from* one currency *to* another.

```http
  GET /api/convert
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `from` | `string` | **Required**. Three-letter currency code to convert from.  |
| `to` | `string` | **Required**. Three-letter currency code to convert to.  |
| `amount` | `float` | **Required**. The amount to be converted.  |
| `date` | `string` | ***Optional***. Date to get historical rates (format YYYY-MM-DD).  |





