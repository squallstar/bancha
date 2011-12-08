######################
ChangeLog
######################

**v 1.0.2** (2011-12-08)

- UPDATING FROM 1.0.x: you need also to merge the "website.php" controller inside "application/controllers" (we just added a new constant) and the "application/xml/Settings.xml"
- Base Reactor (CodeIgniter) has been updated from 2.0.3 to 2.1
- New attribute on the "field" node: "kind" (used by the scheme library. accepted values are "numeric" and "text")
- New library "Schemeforge": creates and updates the custom content types tables: now is possible to automatically create external tables based on the content types
- Huge refactor on the Default dispatcher: page and record routing should gain a 100% speed increase on some queries
- Contents and Pages views have been merged into a new view, accessible from the left menu (Content types)
- Bugfix on the publish log
- API documentation added
- Settings documentation added
- Dispatchers documentation added
- Settings "module" has been semantically renamed to "namespace"
- Little bugfix on the "lang" field of the "Settings.xml" application scheme


**v 1.0.1** (2011-12-05)

- New categories search function added (frontend helper)
- Many languages have been added to CKEditor textarea
- Now you can choose the language during the install setup


**v 1.0** (2011-12-01)

- 1st December, 1.0 is finally live!!!
- New theme: "sandbox", the starting boilerplate for your themes!
- We removed the old two themes (we hate legacy support)
- Rendering process has been re-factored from scratch
- Global variables added
- New front-end helper: we greatly improved the rendering process to make it more designer-friendly
- Designing themes become easier with the new front-end functions


**v 0.9.11** (2011-11-30)

- New "API_ENABLED" configuration constant. GitHub Issue #78 
- Custom redirection after the login process. GitHub Issue #79
- New Resources minifier (a dispatcher) - now JS and CSS resources can be minified
- New function: minify() that accepts an array of resources to be minified (returns an url)


**v 0.9.10** (2011-11-26)

- Content types documentation completed
- Themes documentation added
- Fields documentation added
- Cleanup and more readability on the website config file


**v 0.9.9** (2011-11-22)

- New helper function: semantic_url() that will (try to) generate the detail link of a record.
- The function above, uses also a new ad-hoc function on the model_pages called get_semantic_url().
- We started a new branch on GitHub named "wpthemes". We're working hard to implement the full-compatibility between Bancha and Wordpress themes. More details will be available with the next Bancha releases.


**v 0.9.8** (2011-11-18)

- Fixed a bug with the page address listed on the record edit view when the "prepend language" was disabled.
- Token index removed on SQLite installations (improves the compatibility)


**v 0.9.7** (2011-11-16)

- Major compatibility on the type() and set_type() functions (content and records classes) on fail
- Some improvements on build_data(), build_xml() and related() functions of the record class
- ACL check on the api types() function
- Documentation: added the content types and fields pages


**v 0.9.6** (2011-11-13)

- Some XML nodes have been renamed to remove the underscore (categories, hierarchies, parents, etc...). Please update all your scheme to stay updated with the core xml parser.
- Tables and the primary key on the XML schemes have been merged into a single node
- Fixed a bug on the type_template rendering function (missing .php extension on file_exists)
- Token generation has been changed to improve compatibility and to maximize the performances
- Added a new column on the api_tokens table: content
- Added an index on the token field of the api_tokens table
- Compatibility fix on the administration theme by @dombender

**v 0.9.5** (2011-11-09)

- We are working hard to write all the extended Bancha documentation
- To contribute with the documentation, check the new "core/documentation" folder
- To compile the documentation, you must install Phyton 2.7 + Sphynx. Read the Readme file in the above folder
- You can find the static compiled documentation on the project folder "/documentation"
- Config variable "views_absolute_templates_folder" has been removed
- New documentation theme: Banchize
- Bug fix on the application/config.php (the core config file was loaded instead)


**v 0.9.4** (2011-11-06) Live from #banchafest

- We decided to use an external folder for the application, so we added a "core" folder with the Bancha framework
- Controllers, Helpers and Config files can now be overwrited by the ones placed in the application folder
- Javascript refactor made by @dombender
- Bug fix on the mobile settings variable (View class)


**v 0.9.3** (2011-11-05)

- Wordpress adapter now adds the website first language as record language
- Added a new function on the model_records: id_not_in()
- Now the Tree cache should be always clear the page tree using the website languages (instead of the administration ones)
- We added a new property on the Lang class: $this->lang->default_language
- Now the select fields use the default language of the website (the first of the config array) instead of the current one
- The above change should be reflected around Bancha, so it results in a better language compatibility when using different languages between the admin and the website
- New API method: types() - documentation will be available soon

**v 0.9.2** (2011-11-04)

- Layout fix on the type delete view


**v 0.9.1** (2011-11-03)

- The limit function of the Records, Pages and Users model now will prevent a negative limit to be set
- Page URI now will be trimmed by whitespaces at the end/start of the string
- Content Class got a new function: Simplify (to convert Record objects into arrays)
- New experimental sidebar: Relations
- The mime type text/plain has been added to the CSV adapter
- Added the strpos function to custom.js (same of PHP strpos)
- Bug fix on the add_hash function (custom.js) to improve compatibility on Firefox
- Tree content types now have a relation with their childs by default

**v 0.9.0** (2011-11-01)

- Default type templates views (detail and list) have been refactored
- Corrected a bug on the "where_in" active record function (missed a space after 'AND')
- New admin layout! Re-designed from scratch :)
- Blog premade template: little bug fix on the "published" field
- Added a config variable to set whether multiple tokens can be handle a single username
- The attach_url() helper now correctly skips the language parameter when generates an url
- Added a "separator" parameter to the breadcrumbs helper
- Introduced the relations between record objects (1-0, 1-1, 1-n) - experimental
- New function added to record objects: relation()
- Relations documentation has been added
- New method added to the API system: logout
- Added the API documentation
- Tokens have been slightly changed to improve compatibility between different types of requests
- Many italian translations have been added
- Removed the "username" key on the api_tokens table
- Added a "limit" parameter to the last events controller (dashboard/events)
- Records that are not published will be displayed with a yellow background on the record list
- Added a third parameter (per_page) to the record_list function
- Added a "note" attribute to the description node of each field


**v 0.8.4** (2011-10-25)

- Experimental: API implementation
- New table added: api_tokens
- New controller added: Api_Controller
- New model added: Model_tokens
- Now is possible to login via the new API system
- You can query the records model via the API method "records" to retrieve records or perform many other operations


**v 0.8.3** (2011-10-24)

- Now is possible to choose the theme before installing Bancha
- Bugfix on Javascript for each cycles (only on Webkit browsers)


**v 0.8.2** (2011-10-22)

- Javascript record validation added (validate.js library)
- New node on field schemes: <rules>. You can use the standard CodeIgniter "FormValidation" library rules
- Removed the mandatory node on the field schemes. Now you need to set it into a rule: <rule>required</rule>
- Added a popup when a record form contains some errors (plugin: jquery colorbox)
- Added an escape parameter to the ActiveRecord "where_in" function
- Categories query (dispatcher_default) has been moved inside the "where_in" clause of the next query
- Hierarchies query: same as above (speed increment and two less queries)
- Added the password input field
- Added a "confirm password" field on the users XML scheme
- Clicking on the filename (repository - documents finder) now will attach the file to the textarea


**v 0.8.1** (2011-10-20)

- Import of CSV files is now possible
- New class type: Adapters
- Added a new adapter to handle CSV files
- Added a new adapter to import wordpress xml files
- Wordpress adapter now can import also the post comments
- Refactor of the datetime parser on the Record class
- Visibility field moved (tree types)
- Corrected a bug with the .po files and the record list table headers
- Added many italian localizations to the .po files


**v 0.8** (2011-10-19)

- Local date and datetime format are now applied to new records regarding of the current language (issue #65)
- Theme cookie update (issue #67)
- Added new contributors to Humans.txt file
- The install button will now fade out during the install
- Added a dummy "about us" page on the install default preset
- Added a system that prevent the records to extract twice their documents
- Native php session support added on bootstrap file
- Two teasers on the default theme are now linked to the related content pages
- Theme session switched from cookie to native php session
- Added a loading wheel on the installer
- clear_cache() method has been slighlty improved (model_pages)
- Output class new function: get_cachefile()
- Added the new logo on the left side of the header
- Corrected the "Publish" bug on the record edit (only on Pages content types)
- Current theme name will be appeded to page cache files (prevent the same filename issue on different themes - issue #66)
- Now each content type have its own "feed" view, so you can choose how to render each one

**v 0.7.19** (2011-10-17)

- Cache will not be written when the environment is in staging mode (issue #63)
- Added a cookie to let know a logged user if we have to skip the page-cache thing
- Issue #62 corrected - empty categories generates a query error
- Issue #52 - new PDF generate functions: dispatcher_print and dompdf support added (thx @alexmaroldi)

**v 0.7.18** (2011-10-15)

- Content type list view will be rendered also when there are no records
- Unserialize fix on the settings model
- New favicon!
- Added support for CDATA sections on the xml feed (second param - array - of the add_item() function on the feed lib)

**v 0.7.17** (2011-10-14)

- Added a "bracket" open-close system to CI Active record
- Search queries on the default dispatcher now uses the bracket system to chain conditions
- Unserialized error log patch


**v 0.7.16** (2011-10-13)

- New setting: Maintenance mode (useful for closing temporary the website)
- You can choose between "require login" and "maintenance message"
- Corrected a bug on the datetime fields (only affects the XML columns)


**v 0.7.15** (2011-10-12)

- The function "render_template" of the view class now accepts a fourth parameter to return the output instead echoing it
- The default dispatcher now can handle the pdf files
- New class added: Dispatcher_print (@alexmaroldi is working on it)


**v 0.7.14** (2011-10-12)

- Bug fix corrected on the installer (some people were getting stuck) - thx Marco Solazzi


**v 0.7.13** (2011-10-11)

- Output class now include the GET request when making and retrieving cache files
- Date publish will not be updated when a record will be published


**v 0.7.12** (2011-10-10)

- Dispatcher limit count speed have been improved
- Adding a "search" GET param now let you filter through a content list
- Added a "or_like" function on the Records model

**v 0.7.11** (2011-10-09)

- Now is possible to change the administration public path (check the index.php bootstrap file)
- Documents will be extracted using a single query for all the records (big speed improvement)
- Filenames now will be encrypted by default when uploaded

**v 0.7.10** (2011-10-08)

- View blocks and sections are live! (experimental)
- Automatic meta description implementation
- Users got a "admin_lang" field with the language used in the administration
- Little refactors of the Settings model

**v 0.7.9** (2011-10-04)

- Experimental use of "block templates"
- Fixed a bug on the "published" field of the content types
- Image dispatcher routes now allows uppercase extensions
- Fixed a bug on the route action (website controller)


**v 0.7.8** (2011-10-03)

- Multilanguage URI support (issue #51)
- Website homepage is now a record (of type page)
- Some fixes on the footer of the front-end themes
- Language will be also included on new records if the content type supports it
- New administration panel: themes


**v 0.7.7** (2011-10-01)

- New sidebar icons (fieldset node - xml scheme)
- Description node slightly changed (xml scheme)