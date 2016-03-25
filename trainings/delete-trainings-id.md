---
title: DELETE trainings/:id
---

Receives a training ID.  
Returns an empty response with status code of `204`.  
It deletes a specific training.

## Parameters

Parameter			| Type      | Description
---- | ---- | ---- 
id _required_		| String	| Refers to the training ID

### Example request

```
DELETE https://api.evscalculator.com/v1/trainings/bglRdqG4no
```