## Bancha Change Log file ##

**v 0.7.19** (2011-10-17)

- Cache will not be written when the environment is in staging mode (issue #63)
- Added a cookie to let know a logged user if we have to skip the page-cache thing


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