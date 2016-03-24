---
title: GET trainings/:id/records
---


Returns the listing with the record information about a specific training.

## Parameters

Parameter       | Type          | Description
----          | ----          | ----
id _required_     | Integer, positive   | Refers to the training ID
stat _required_     | String        | You can filter them by stat.
horde           | Boolean       | Whether it was from using an horde or not.
vitamin         | Boolean       | Whether it was from using a vitamin or not.
pokerus         | Boolean         | Filter by pokerus used/not.

Example: `https://api.evscalculator.com/v1/trainings/13/records.json`