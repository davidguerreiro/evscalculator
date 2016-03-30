---
title: GET trainings/:id/actions/:stat
---

- Receives a training ID and a stat name. 
- Returns a single object with a status code of `200`.
- It provides a collection of hordes, vitamins and berries in order of preference for that stat and training. It also provides a recommended set of options for a "perfect training" or "what to do next" for a specific scenario of a number of EVs, a target and a stat.

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
		"recommended": {
			"pokerus": true,
			"porwer_item": true,
			"macho_braze": true,
			"training": [
				{
					"type": "vitamins",
					"times": 10,
					"done": 0,
					"options": [
						{
							"name": "Protein",
							"value": 10
						}
					]
				},
				{
					"type": "hordes",
					"times": 10,
					"done": 0,
					"options": [
						{
							"id": 12,
							"name": "Arbok",
							"value": 20
						},
						{
							"id": 13,
							"name": "Trevenant",
							"value": 20
						}
					]
				}
			]
		],
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