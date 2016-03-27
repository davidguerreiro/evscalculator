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
from		| String			| Exmplains how the EVs where gained
game          		| Numeric, positive | Numeric ID that represents the game used. 
pokerus         	| Boolean         	| False by default.
timestamp         	| Integer, positive | False by default.

The `from` parameter is a `type:id` pair to represent the origin of this gain/loss. Example: `horde:55` represents this record comes from killing a horde of PoKémons with ID 55.

### Example request 

```
POST {{ site.api_url }}/v1/trainings/bglRdqG4no/records
```

### Example response

```json
{
	
}
```
