---
title: POST trainings/:id/records
---

- Receives a training ID and the data for a new record.
- Returns a single object with a status code of `201` and `Location` header for the new item.
- It provides the data of the created record.

## Parameters

Parameter       	| Type          	| Description
---- | ---- | ---- 
id _required_     	| String   			| Refers to the training ID
value _required_    | Integer       	| Number of EVs gained/lost
stat _required_     | String        	| Must refer to a stat from training
from _required_		| String			| Exmplains how the EVs where gained
game          		| Numeric, positive | Numeric ID that represents the game used. 
pokerus         	| Boolean         	| False by default.
timestamp         	| Integer, positive | False by default.


### Example request 

```
POST {{ site.api_url }}/v1/trainings/bglRdqG4no/records
```

### Example response

```json
{
	
}
```
