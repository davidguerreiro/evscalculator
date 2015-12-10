#Using the API

Available at api.evscalculator.com, for any AJAX requests and sync with offline usage.
This API is limited to internal use at the moment, so CORS is enabled but limited to the site.

If you are working on a project that could use the API, please [open an issue](https://github.com/davidguerreiro/evscalculator/issues/new) and we'll discuss it.

Requests to the API should be made like:

```
http://api.evscalculator.com/v1/request
```

Where request is the action requested. GET represents requests with static results, that accept no parameters. POST represents requests that have parameters and dynamic results.
Parameters should be passed via the URL.


#### GET hordes
Contains all information about hordes. This simply returns the file `hordes.json`.


#### GET vitamins
Contains all information about vitamins. This simply returns the file `vitamins.json`.


#### POST new-training
Receives the data necessary for a new training, returns information and IDs about it if successful.

Parameter		| Type	 					| Description
---- 			| ----	 					| ----
id_user			| Integer, 0 or positive 	| If a user is logged in, then we send her ID.
hp, attack, defense, spattack, spdefense, speed _requires at least one positive_ |  Integer, 1 to 252 | These values represent the stats which are to be trained.
game 			|  Numeric, positive 		| Numeric ID that represents the game used. 
pokerus 		|  Boolean 					| False by default.
power_brace 	|  Boolean 					| False by default.
sturdy_object 	|  Boolean					| False by default.
timestamp 		|  Integer, positive		| False by default.

	
#### POST record
Receives the data for an action that changes the EVs count on a specific stat.
Returns result of adding a record to the history.

Parameter				| Type	 				| Description
---- 					| ----	 				| ----
id_training _required_	| Integer, positive	 	| Refers to the training ID
stat_value _required_	| Integer				| Number of EVs gained
stat_name _required_	| String				| Must refer to a stat from training
id_horde 				| Numeric, positive 	| Numeric ID that represents the horde used (if any).
id_vitamin 				| Numeric, positive 	| Numeric ID that represents the vitamin used (if any).
game 					| Numeric, positive 	| Numeric ID that represents the game used. 
pokerus 				| Boolean 				| False by default.
timestamp 				| Integer, positive		| False by default.

