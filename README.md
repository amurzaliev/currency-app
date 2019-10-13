## Installation ##
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

Check API:
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