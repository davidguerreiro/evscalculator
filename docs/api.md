#Using the API

Available at api.evscalculator.com, for any AJAX requests and sync with offline usage.
This API is limited to internal use at the moment, so CORS is enabled but limited to the site.

If you are working on a project that could use the API, please [open an issue](https://github.com/davidguerreiro/evscalculator/issues/new) and we'll discuss it.

Requests to the API should be made like:

```
https://api.evscalculator.com/v1/request.json
```

Where request is the action requested. GET represents requests with static results, that accept no parameters. POST represents requests that have parameters and dynamic results.
Parameters should be passed via the URL. JSON format would be the only one supported for now but the code should be independent of the format. You can remove the `.json` extension and it will fallback to JSON.

##Using JSONP
The API supports JSONP for old browser support on every valid endpoint. To get the JSONP return you must:

* Specify a **callback** parameter, which must be a string.
* Use JSON format or no format at all (fallbacks to JSON).

Example: `https://api.evscalculator.com/v1/hordes?callback=foo`

### GET hordes
Contains all information about hordes.
Can be filtered by stat or game.

###Parameters

Parameter		| Type		| Description
---- 			| ----		| ----
stat			| String 	| Filter by stat name.
game			| Integer 	| Filter by game edition.

Example: `https://api.evscalculator.com/v1/hordes.json?stat=hp`

```javascript
[
	{
		"name": "Gulpin",
		"stat_name": "hp",
		"stat_value": 5,
		"game": 0
	},
	{
		"name": "Whismur",
		"stat_name": "hp",
		"stat_value": 5,
		"game": 0
	},
	{
		"name": "Slowpoke",
		"stat_name": "hp",
		"stat_value": 5,
		"game": 0
	},
	{
		"name": "Foongus",
		"stat_name": "hp",
		"stat_value": 5,
		"game": 0
	},
	{
		"name": "Lickitung",
		"stat_name": "hp",
		"stat_value": 5,
		"game": 0
	}
]
```



### GET vitamins
Contains all information about vitamins. This simply returns the file `vitamins.json`.
Can be filtered by stat or game.

###Parameters

Parameter		| Type		| Description
---- 			| ----		| ----
stat			| String 	| Filter by stat name.
game			| Integer 	| Filter by game edition.

Example: `https://api.evscalculator.com/v1/vitamins.json`




### POST trainings
Receives the data necessary for a new training, returns information and IDs about it if successful.

###Parameters

Parameter		| Type	 					| Description
---- 			| ----	 					| ----
id_user			| Integer, 0 or positive 	| If a user is logged in, then we send her ID.
hp, attack, defense, spattack, spdefense, speed _requires at least one positive_ |  Integer, 1 to 252 | These values represent the stats which are to be trained.
game 			|  Numeric, positive 		| Numeric ID that represents the game used. 
pokerus 		|  Boolean 					| False by default.
power_brace 	|  Boolean 					| False by default.
sturdy_object 	|  Boolean					| False by default.
timestamp 		|  Integer, positive		| False by default.

Example: `https://api.evscalculator.com/v1/trainings.json`




### GET trainings/:id/records
Returns the listing with the record information about a specific training.

###Parameters

Parameter				| Type	 				| Description
---- 					| ----	 				| ----
id _required_			| Integer, positive	 	| Refers to the training ID
stat _required_			| String				| You can filter them by stat.
horde 					| Boolean			 	| Whether it was from using an horde or not.
vitamin 				| Boolean			 	| Whether it was from using a vitamin or not.
pokerus 				| Boolean 				| Filter by pokerus used/not.

Example: `https://api.evscalculator.com/v1/trainings/13/records.json`



### POST trainings/:id/records
Receives the data for an action that changes the EVs count on a specific stat.
Returns success/fail status.

###Parameters

Parameter				| Type	 				| Description
---- 					| ----	 				| ----
id _required_			| Integer, positive	 	| Refers to the training ID
value _required_		| Integer				| Number of EVs gained
stat _required_			| String				| Must refer to a stat from training
id_horde 				| Numeric, positive 	| Numeric ID that represents the horde used (if any).
id_vitamin 				| Numeric, positive 	| Numeric ID that represents the vitamin used (if any).
game 					| Numeric, positive 	| Numeric ID that represents the game used. 
pokerus 				| Boolean 				| False by default.
timestamp 				| Integer, positive		| False by default.

Example: `https://api.evscalculator.com/v1/trainings/13/records.json`




### POST users
Creates an user.

###Parameters

Parameter			| Type	 				| Description
---- 				| ----	 				| ----
email _required_	| String				| Email of the user.
id_training			| Integer, positive	 	| Refers to the training ID (if any)
timestamp 			| Integer, positive		| False by default.

Example: `https://api.evscalculator.com/v1/users.json`




### GET users/:id
Returns a user profile with training info.

###Parameters

Parameter		| Type					| Description
---- 			| ----					| ----
id _required_	| Integer, positive 	| Unique ID for user

Example: `https://api.evscalculator.com/v1/users/12.json`





