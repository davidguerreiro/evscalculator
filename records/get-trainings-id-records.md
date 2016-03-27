---
title: GET trainings/:id/records
---

- Receives a training ID.  
- Returns an object with a status code of `200`.
- It provides the history record for a training, separated by stat.

## Parameters

Parameter       | Type          		| Description 
---- | ---- | ---- 
id _required_   | String			   	| Refers to the training ID


### Example request

```
GET {{ site.api_url }}/v1/trainings/bglRdqG4no/records
```

### Example response

```
{
  	"stat": "ok",
  	"count": 1,
  	"data": {
  		"attack": [
	  		{
	  			"id": "bkE98qWtRb",
	  			"horde": false,
	  			"vitamin": false,
	  			"value": 10,
			    "timestamp": "2016-03-22 03:51:07"
	  		},
	  		{
	  			"id": "oiqYFg0Psz",
	  			"horde": 55,
	  			"vitamin": false,
	  			"value": 5,
			    "timestamp": "2016-03-22 03:51:07"
	  		}
  		]
  	}
}
```