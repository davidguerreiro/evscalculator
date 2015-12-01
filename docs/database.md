**Training**: Training table saves all the data for a single training session. 

Field | Type | Description
---- | ---- | ----
**id** | INT(11) NOT_NULL AUTO_INCREMENT | Identification number
**id_url** | VARCHAR(255) DEFAULT NULL | Url which allows users to load an unfinished training.
**id_user** | INT(11) NOT_NULL DEFAULT 0 | User number. This value will be always 0 because the first version doesn't support user creation
**hp** | INT(3) NOT_NULL DEFAULT 0 | Hp stat value
**attack** | INT(3) NOT_NULL DEFAULT 0 | Attack stat value
**defense** | INT(3) NOT_NULL DEFAULT 0 | Defense stat value
**spattack** | INT(3) NOT_NULL DEFAULT 0 | Sp Attack stat value
**spdefense** | INT(3) NOT_NULL DEFAULT 0 | Sp Defense stat value
**speed** | INT(3) NOT_NULL DEFAULT 0 | Speed stat value
**game** | INT(2) NOT_NULL DEFAULT 0 | Pokemon 6th gen game version (0 -- XY Pokemon games, 1 -- Ruby Omega Alpha Sapphire Pokemon Games)
**pokerus** | TINYINT(1) NOT_NULL DEFAULT 0 | Pokerus (0 -- disabled, 1 -- enabled)
**power_brace** | TINYINT(1) NOT_NULL DEFAULT 0 | Power brace (0 -- unequipped, 1 -- equipped)
**sturdy_object** | TINYINT(1) NOT_NULL DEFAULT 0 | Sturdy object (0 -- unequipped, 1 -- equipped)
**timestamp** | TIMESTAMP |



**Records**: Records will provide us with information about how users are using the application, which version of the game is the most used and more

Field | Type | Description
---- | ---- | ----
**id** | INT(11) NOT_NULL AUTO_INCREMENT | Identification number
**id_training** | INT(11) NOT_NULL DEFAULT 0 | This id related the record with a single training session
**stat_name** | VARCHAR(50) | Name of the stat what we are adding or decreasing the value
**stat_value** | INT(3) NOT_NULL DEFAULT 0 | Amont of evs that we are adding or subtracting
**id_horde** | INT(11) NOT_NULL DEFAULT 0 | The ID of the horde when a horde has been used during the training session
**id_vitamin** | INT(11) NOT_NULL DEFAULT 0 | The ID of the vitamin when a vitamin has been used during the training session
**game** | INT(3) NOT_NULL DEFAULT 0 | Pokemon 6th gen game version (0 -- XY Pokemon games, 0 -- Ruby Omega Alpha Sapphire Pokemon Games)
**pokerus** | TINYINT(1) NOT_NULL DEFAULT 0 | Pokerus (0 -- disabled, 1 -- enabled)
**timestamp** | TIMESTAMP |

**User**: First approach of the User basic table. Not used in the first version.

Field | Type | Description
---- | ---- | ----
**id** | INT(11) NOT_NULL AUTO_INCREMENT | Identification number
**name** | VARCHAR(50) NOT_NULL DEFAULT ''| The name of the user
**email** | VARCHAR(255) NOT_NULL DEFAULT '' | The email address of the user
**timestamp** | TIMESTAMP |
