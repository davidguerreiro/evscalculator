---
title: GET trainings/:id/actions/:stat
---

- Receives a training ID and a stat name. 
- Returns a single object with a status code of `200`.
- It provides a collection of hordes, vitamins and berries in order of preference for that stat and training.

## Parameters

Parameter       | Type          		| Description
---- | ---- | ---- 
id _required_   | String          | Refers to the training ID
name _required_   | String          | Refers to the stat name


### Example request 

```
GET {{ site.api_url }}/v1/trainings/bglRdqG4no/actions/attack
```

### Example response

```json
{
  "stat": "ok",
  "count": 1,
  "data": {
    "hordes": [
		[...]
	],
	"vitamins": [
		[...]
	],
	"berries": [
		[...]
	]
  }
}
```