## Bancha Change Log file ##

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