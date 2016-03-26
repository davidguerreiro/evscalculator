---
title: GET status
---

- Returns an object array.  
- It provides the current status of the app.


### Example request

```
GET https://api.evscalculator.com/v1/status
```

### Example response

```json
{
	"stat": "ok",
	"count": 1,
	"data": {
		"app": {
			"online": true,
			"database": true
		},
		"users": {
			"total": 130,
			"active": 25
		},
		"trainings": {
			"created": 130,
			"completed": 90,
			"private": 50
		},
		"records": {
			"total": 130,
			"most_used": "hordes"
		},
		"games": {
			"available": 2,
			"most_used": 0
		},	
		"hordes": {
			"total": 55,
			"most_used": 10
		},
		"vitamins": {
			"total": 20,
			"most_used": 2
		}
	}
}
```