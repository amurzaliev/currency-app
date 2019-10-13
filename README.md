# Installation #
1. Configure .env.local
2. Configure database
```
bin/console d:d:c
bin/console d:s:c
```
3. Run import command
```
bin/console app:parse-data [ecb, cbr]  // ecb - default
```

# API #
```
GET: /api/convert

Params:
from - <string> currency (usd, rub, eur etc.)
to - <string> currency (usd, rub, eur etc.)
value - <int> amount

Response format:
{
    source: <string>, // Data source
    from: <string>,
    to: <string>,
    value: <int>,
    convertedValue: <float>
}
```

### Warning ###
The App doesn't use [BC Math](https://www.php.net/manual/en/book.bc.php) or any related libraries for working with float numbers (just demo project).