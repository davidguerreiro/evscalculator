---
title: GET trainings/:id/records/:stat
---

- Receives a training ID.  
- Returns an object array with a status code of `200`.
- It provides the history record for a stat in a training.

## Parameters

Parameter       | Type          		| Description 
---- | ---- | ---- 
id _required_   | String			   	| Refers to the training ID
stat _required_   | String			   	| Refers to the stat name.


### Example request

```
GET {{ site.api_url }}/v1/trainings/bglRdqG4no/records/attack
```

### Example response

```
{
  	"stat": "ok",
  	"count": 1,
  	"data": [
  		{
        "id": "eywTvietR8",
        "value": 10,
        "timestamp": "2016-03-22 03:51:07"
      },
      {
        "id": "bkE98qWtRb",
        "value": 10,
        "timestamp": "2016-03-22 03:51:07",
        "from": {
          "type": "hordes",
          "origin": {  
            "id":11,
            "name":"Weepinbell",
            "stat_name":"attack",
            "stat_value":10,
            "game":0
          }
        }
      },
      {
        "id": "oiqYFg0Psz",
        "value": 10,
        "timestamp": "2016-03-22 03:51:10",
        "from": {
          "type": "berries",
          "origin": {
            "name": "Kelpsy Berry",
            "stat_name": "attack",
            "stat_value": 10
          }
        }
      }
	]
}
```