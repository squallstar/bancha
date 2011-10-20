## Bancha Change Log file ##

**v 0.8.1** (2011-10-20)

- Import of CSV files is now possible
- New class type: Adapters
- Added a new adapter to handle CSV files
- Added a new adapter to import wordpress xml files


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