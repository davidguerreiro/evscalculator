---
title: GET berries
implemented : true
---

- Returns an object array.  
- It provides all the information about berries.

## Parameters

Parameter   | Type    | Description
----      | ----    | ----
stat      | String  | Filter by stat name.


### Example request

```
GET {{ site.api_url }}/v1/berries
```

### Example response


```json
{
  "stat": "ok",
  "count": 6,
  "data": [
    {
      "name": "Pomeg Berry",
      "stat_name": "hp",
      "stat_value": 10
    },
    {
      "name": "Kelpsy Berry",
      "stat_name": "attack",
      "stat_value": 10
    },
    {
      "name": "Qualot Berry",
      "stat_name": "defense",
      "stat_value": 10
    },
    {
      "name": "Hondew Berry",
      "stat_name": "spattack",
      "stat_value": 10
    },
    {
      "name": "Grepa Berry",
      "stat_name": "spdefense",
      "stat_value": 10
    },
    {
      "name": "Tamato Berry",
      "stat_name": "speed",
      "stat_value": 10
    }
  ]
}
```