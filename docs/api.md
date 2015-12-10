#Using the API

Available at api.evscalculator.com, for any AJAX requests and sync with offline usage.
This API is limited to internal use at the moment, so CORS is enabled but limited to the site.
If you have a project that could use the API, please [open an issue](https://github.com/davidguerreiro/evscalculator/issues/new) and we'll discuss it.

## Static requests
- /hordes.json: Contains all information about hordes.
- /vitamins.json: Contains all information about vitamins.


## Dynamic requests
You can send some parameters to these requests via GET and get different results. They represent the actions done on the site.

#### new-training.json — Returns contents for a new training.

Parameter		| Required	 | Type	 					| Description
---- 			| ----		 | ----	 					| ----
id_user			| No		 | Integer, 0 or positive 	| If a user is logged in, then we send her ID.
hp, attack, defense, spattack, spdefense, speed 			| One positive at least |  Integer, 1 to 252 | These values represent the stats which are to be trained. At least one must have been received to be a valid training session.
game 			| No 		|  Numeric, positive 		| Numeric ID that represents the game used. If not specified, fallbacks to XY's ID
pokerus 		| No 		|  Boolean 					| If not sent, it must be assumed the trainer doesn't have it.
power_brace 	| No 		|  Boolean 					| If not sent, it must be assumed the trainer doesn't have it.
sturdy_object 	| No 		|  Boolean					| If not sent, it must be assumed the trainer doesn't have it.
timestamp 		| No 		|  Integer, positive		| If not sent, it must be assumed the trainer doesn't have it.


	
#### record.json — Returns result of adding a record to the history.

Parameter		| Required	 | Type	 					| Description
---- 			| ----		 | ----	 					| ----
id_training		| Yes		 | Integer, positive	 	| Refers to the training ID
stat_value		| Yes		 | Integer				 	| Number of EVs gained
stat_name		| Yes		 | String				 	| Must refer to a stat from training
id_horde 		| No 		|  Numeric, positive 		| Numeric ID that represents the horde used to gain those EVs.
id_vitamin 		| No 		|  Numeric, positive 		| Numeric ID that represents the vitamin used to gain those EVs.
game 			| No 		|  Numeric, positive 		| Numeric ID that represents the game used. If not specified, fallbacks to XY's ID
pokerus 		| No 		|  Boolean 					| If not sent, it must be assumed the trainer doesn't have it.
timestamp 		| No 		|  Integer, positive		| If not sent, it must be assumed the trainer doesn't have it.
