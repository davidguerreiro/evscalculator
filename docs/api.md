#Using the API

Available at api.evscalculator.com, for any AJAX requests and sync with offline usage.
This API is limited to internal use at the moment, so CORS is enabled but limited to the site.

If you are working on a project that could use the API, please [open an issue](https://github.com/davidguerreiro/evscalculator/issues/new) and we'll discuss it.

Requests to the API should be made like:

```
https://api.evscalculator.com/v1/request.json
```

Where request is the action requested. GET represents requests with static results, that accept no parameters. POST represents requests that have parameters and dynamic results.
Parameters should be passed via the URL. JSON format would be the only one supported for now but the code should be independent of the format.


### GET hordes
Contains all information about hordes. This simply returns the file `hordes.json`.
Can be filtered by stat or game.

Example: `https://api.evscalculator.com/v1/hordes.json`

Parameter		| Type		| Description
---- 			| ----		| ----
stat_name		| String 	| Filter by stat name.
game			| Integer 	| Filter by game edition.



### GET vitamins
Contains all information about vitamins. This simply returns the file `vitamins.json`.
Can be filtered by stat or game.

Example: `https://api.evscalculator.com/v1/vitamins.json`

Parameter		| Type		| Description
---- 			| ----		| ----
stat_name		| String 	| Filter by stat name.
game			| Integer 	| Filter by game edition.



### POST trainings/create
Receives the data necessary for a new training, returns information and IDs about it if successful.

Example: `https://api.evscalculator.com/v1/trainings/create.json`

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

	

### GET trainings/:id/records
Returns the listing with the record information about a specific training.

Example: `https://api.evscalculator.com/v1/trainings/13/records/create.json`

###Parameters

Parameter				| Type	 				| Description
---- 					| ----	 				| ----
id _required_			| Integer, positive	 	| Refers to the training ID
stat _required_			| String				| You can filter them by stat.
horde 					| Boolean			 	| Whether it was from using an horde or not.
vitamin 				| Boolean			 	| Whether it was from using a vitamin or not.
pokerus 				| Boolean 				| Filter by pokerus used/not.



	
### POST trainings/:id/records/create
Receives the data for an action that changes the EVs count on a specific stat.
Returns success/fail status.

Example: `https://api.evscalculator.com/v1/trainings/13/records/create.json`

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



### POST users/create/:email
Creates an user.

Example: `https://api.evscalculator.com/v1/users/create/test%40evscalculator.com.json`

###Parameters

Parameter			| Type	 				| Description
---- 				| ----	 				| ----
email _required_	| String				| Email of the user.
id_training			| Integer, positive	 	| Refers to the training ID (if any)
timestamp 			| Integer, positive		| False by default.



### GET users/:id
Returns a user profile with training info.

Example: `https://api.evscalculator.com/v1/users/12.json`

###Parameters

Parameter		| Type					| Description
---- 			| ----					| ----
id _required_	| Integer, positive 	| Unique ID for user





