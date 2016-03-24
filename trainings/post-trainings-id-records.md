---
title: POST trainings/:id/records
---

Receives the data for an action that changes the EVs count on a specific stat.
Returns success/fail status.

## Parameters

Parameter       | Type          | Description
----          | ----          | ----
id _required_     | Integer, positive   | Refers to the training ID
value _required_    | Integer       | Number of EVs gained
stat _required_     | String        | Must refer to a stat from training
id_horde        | Numeric, positive   | Numeric ID that represents the horde used (if any).
id_vitamin        | Numeric, positive   | Numeric ID that represents the vitamin used (if any).
game          | Numeric, positive   | Numeric ID that represents the game used. 
pokerus         | Boolean         | False by default.
timestamp         | Integer, positive   | False by default.

Example: `https://api.evscalculator.com/v1/trainings/13/records.json`


