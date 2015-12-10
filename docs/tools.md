#Contributing and tools

If you want to contribute, you'll need to use the tools we use and follow the same process.

## Conventions

- **api** folder to put PHP files that correspond to the AJAX requests. This would be the root for api.evscalculator.com
- **scss** folder will contain all the Sass files, which will combine in a single style.css file. There should never be more than one CSS file requested per HTML documents (unless it's a fallback for a form).
- **js** folder for all JavaScript files.
- **lib** for all PHP files that are used on the templates (never the ones used on the API, these should be theme-specific should be separate).
- **img** for static files and any pokemon images, where _img_ would be the root for img.evscalculator.com. This way the image system would have the ability to grow if we need to use different images for every pokemon.
    - **img/pkmn/small** Will contain the small version of the images.
    - **img/pkmn/medium** Will contain the normal version of the images.



## Markdown and GitHub workflow

We use the GitHub workflow, using issues as a new feature discussion platform and task manager. You may need to know: 

**Markdown**
- Explanation: http://www.remarq.io/articles/five-minutes-to-markdown-mastery/
- Practice: http://markdowntutorial.com/

**GitHub**
- How to use GitHub's website and basic conceptos: https://guides.github.com/activities/hello-world/
- Process (flow): https://guides.github.com/introduction/flow/
- Issues: https://guides.github.com/features/issues/
- Where to use Markdown on GitHub: https://guides.github.com/features/mastering-markdown/
- How to use GitHub for Windows app: https://www.youtube.com/watch?v=u12zHGRfiKU



## Flat development

We use the `gh-pages` branch to store the flats for the project. These flats can be accessed online at http://davidguerreiro.com/evscalculator/.

To organize the CSS we use Sass (SCSS syntax) and since there are not many dependencies we'll skip grunt or gulp for now. We are using [CodeKit](https://incident57.com/codekit/) to compile the Sass and merge the JS files.