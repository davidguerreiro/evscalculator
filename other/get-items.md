---
title: GET items
---

- Returns an object array.  
- It provides all the items you can equip a PoKémon with and their effects.

## Parameters

Parameter   | Type    | Description
----      | ----    | ----
stat      | String  | Filter by stat name.
id      | String  | Returns only the ID-matching item.


### Example request

```
GET {{ site.api_url }}/v1/items
```

### Example response


```json
{
  "stat": "ok",
  "count": 6,
    "data": [
      {
        "id": 1,
        "name": "Macho Brace",
        "op": "by",
        "value": 2
      },
      {
        "id": 2,
        "name": "Power Anklet",
        "stat": "speed",
        "op": "add",
        "value": 20
      },
      {
        "id": 3,
        "name": "Power Band",
        "stat": "spdefense",
        "op": "add",
        "value": 20
      },
      {
        "id": 4,
        "name": "Power Belt",
        "stat": "defense",
        "op": "add",
        "value": 20
      },
      {
        "id": 5,
        "name": "Power Bracer",
        "stat": "attack",
        "op": "add",
        "value": 20
      },
      {
        "id": 6,
        "name": "Power Lens",
        "stat": "spattack",
        "op": "add",
        "value": 20
      },
      {
        "id": 7,
        "name": "Power Weight",
        "stat": "hp",
        "op": "add",
        "value": 20
      }
  ]
}
```