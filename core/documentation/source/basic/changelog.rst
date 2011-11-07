######################
ChangeLog
######################

**v 0.9.4** (2011-10-06) Live from #banchafest

- We decided to use an external folder for the application, so we added a "core" folder with the Bancha framework
- Controllers, Helpers and Config files can now be overwrited by the ones placed in the application folder
- Javascript refactor made by @dombender
- Bug fix on the mobile settings variable (View class)


**v 0.9.3** (2011-10-05)

- Wordpress adapter now adds the website first language as record language
- Added a new function on the model_records: id_not_in()
- Now the Tree cache should be always clear the page tree using the website languages (instead of the administration ones)
- We added a new property on the Lang class: $this->lang->default_language
- Now the select fields use the default language of the website (the first of the config array) instead of the current one
- The above change should be reflected around Bancha, so it results in a better language compatibility when using different languages between the admin and the website
- New API method: types() - documentation will be available soon

**v 0.9.2** (2011-10-04)

- Layout fix on the type delete view


**v 0.9.1** (2011-10-03)

- The limit function of the Records, Pages and Users model now will prevent a negative limit to be set
- Page URI now will be trimmed by whitespaces at the end/start of the string
- Content Class got a new function: Simplify (to convert Record objects into arrays)
- New experimental sidebar: Relations
- The mime type text/plain has been added to the CSV adapter
- Added the strpos function to custom.js (same of PHP strpos)
- Bug fix on the add_hash function (custom.js) to improve compatibility on Firefox
- Tree content types now have a relation with their childs by default

**v 0.9.0** (2011-10-01)

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