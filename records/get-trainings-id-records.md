---
title: GET trainings/:id/records
---

Receives a training ID.  
Returns an object array with a status code of `200`.
It provides the history record for a training.

## Parameters

Parameter       | Type          		| Description 
---- | ---- | ---- 
id _required_   | String			   	| Refers to the training ID


### Example request

```
GET https://api.evscalculator.com/v1/trainings/bglRdqG4no/records
```

### Example response