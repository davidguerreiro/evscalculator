---
title: GET trainings/:id/summary
implemented: false
---

- Receives a training ID.  
- Returns a single object with the training summary and a status code of `200`.
- It provides a summary about records across stats.

## Parameters

Parameter       | Type          		| Description
---- | ---- | ---- 
id _required_   | String			   	| Refers to the training ID


### Example request 

```
GET {{ site.api_url }}/v1/trainings/bglRdqG4no/summary
```

### Example response

```json
{
  "stat": "ok",
  "count": 1,
  "data": {
    "evs": {
      "manual": 120,
      "hordes": 300,
      "vitamins": 30,
      "berries": -20
    },
    "times_used": {
      "manual": 12,
      "hordes": 10,
      "vitamins": 3,
      "berries": 2
    },
  }
}
```