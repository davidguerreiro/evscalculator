---
title: GET trainings/:id/:stat
---

- Receives a training ID and a stat name. 
- Returns a single object with a status code of `200`.
- It provides the the target and current progress for a specific stat in a training.

## Parameters

Parameter       | Type          		| Description
---- | ---- | ---- 
id _required_   | String          | Refers to the training ID
name _required_   | String          | Refers to the stat name


### Example request 

```
GET https://api.evscalculator.com/v1/trainings/bglRdqG4no/attack
```

### Example response

```json
{
  "stat": "ok",
  "count": 1,
  "data": {
    "target": 230,
    "progress": 110
  }
}
```