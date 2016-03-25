---
title: JSONP
---

The API supports JSONP for old browser support on every valid endpoint. To get the JSONP response you must:

* Specify a **callback** parameter, which must be a string.
* Use JSON format or no format at all (fallbacks to JSON).

The response will then have a `application/javascript` MIME type.

### Example request

`GET https://api.evscalculator.com/v1/hordes?callback=foo`

### Example response

```json
foo({  
   "stat":"ok",
   "count":55,
   "data":[  
      {  
         "id":21,
         "name":"Graveler",
         "stat_name":"defense",
         "stat_value":10,
         "game":0
      },
      [ … ]
   ]
})
```