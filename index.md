---
title: EVs Calculator API
---

Available at `api.evscalculator.com`, this API is open to public use, so CORS is enabled but limited on some endpoints.
Bear in mind this is an **experimental** API at this stage.

If you are working on a project that could use the API, please [open an issue on GitHub](https://github.com/davidguerreiro/evscalculator/issues/new), we would love to hear about it.

Requests to the API should be made like:

```
https://api.evscalculator.com/v1/endpoint
```

Where the endpoint shall be one of the availables on the sidebar.

JSON format would be the only one supported for now but the code should be independent of the format. You can also add the `.json` extension or try the experimental `.xml` an `.csv` formats.

All responses would have a MIME type of `application/json` unless JSONP is used.
