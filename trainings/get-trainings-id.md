---
title: GET trainings/:id
---

Returns a specific training.

## Parameters

Parameter       | Type          		| Description
---- | ---- | ---- 
id _required_   | String			   	| Refers to the training ID


### Example request 

`GET https://api.evscalculator.com/v1/trainings/bglRdqG4no`

### Example response

```json
{
  "stat": "ok",
  "count": 1,
  "data": {
    "id": "bglRdqG4no",
    "id_user": 0,
    "hp": 0,
    "attack": 230,
    "defense": 0,
    "spattack": 0,
    "spdefense": 0,
    "speed": 0,
    "game": 0,
    "pokerus": 0,
    "sturdy_object": 0,
    "timestamp": "2016-03-22 03:51:07"
  }
}
```