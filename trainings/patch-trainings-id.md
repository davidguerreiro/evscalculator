---
title: PATCH trainings/:id
---

- Receives a training ID, an operation, a field and an origin or value.  
- Returns a single object with a status code of `200`.
- It updates a single value for a specific training.


## Parameters

Parameter   | Type            | Description
---- | ---- | ---- 
op _required_ |  String | Can be `test`, `replace`, `remove`.
field _required_ |  String | Can be power_item or pokerus for now.
value | Any | Required for every operation but remove.

### Example request 

```
PATCH {{ site.api_url }}/v1/trainings
```

### Example response

The response should be the same as [GET trainings/:id](/trainings/get-trainings-id/), returning the updated training.