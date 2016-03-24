---
title: EVs Calculator API docs
---

## Using the API

Available at api.evscalculator.com, for any AJAX requests and sync with offline usage.
This API is limited to internal use at the moment, so CORS is enabled but limited to the site.

If you are working on a project that could use the API, please [open an issue](https://github.com/davidguerreiro/evscalculator/issues/new) and we'll discuss it.

Requests to the API should be made like:

```
https://api.evscalculator.com/v1/request.json
```

Where request is the action requested. GET represents requests with static results, that accept no parameters. POST represents requests that have parameters and dynamic results.
Parameters should be passed via the URL. JSON format would be the only one supported for now but the code should be independent of the format. You can remove the `.json` extension and it will fallback to JSON.

## Using JSONP
The API supports JSONP for old browser support on every valid endpoint. To get the JSONP return you must:

* Specify a **callback** parameter, which must be a string.
* Use JSON format or no format at all (fallbacks to JSON).

Example: `https://api.evscalculator.com/v1/hordes?callback=foo`