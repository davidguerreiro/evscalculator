---
title: GET trainings/:id
---

- Receives a training ID.  
- Returns a single object with the training data and a status code of `200`.
- It provides the information about a specific training.

## Parameters

Parameter       | Type          		| Description
---- | ---- | ---- 
id _required_   | String			   	| Refers to the training ID


### Example request 

```
GET https://api.evscalculator.com/v1/trainings/bglRdqG4no
```

### Example response

```json
{
  "stat": "ok",
  "count": 1,
  "data": {
    "id": "bglRdqG4no",
    "game": 0,
    "pokerus": false,
    "sturdy_object": false,
    "timestamp": "2016-03-22 03:51:07",
    "target": {
      "hp": 0,
      "attack": 230,
      "defense": 0,
      "spattack": 0,
      "spdefense": 0,
      "speed": 0
    },
    "progress": {
      "hp": 0,
      "attack": 110,
      "defense": 0,
      "spattack": 0,
      "spdefense": 0,
      "speed": 0
    }
  }
}
```