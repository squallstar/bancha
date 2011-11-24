================
Validation rules
================

Each field can implement its custom validation rules using the **<rules>** node.
The defined rules will be used using the **validate.js** library by **Rick Harrison**. This library implements all the original CodeIgniter validation library methods dividing them by a single pipe "|". Feel free to use them as you need.

Basic usage that defines a field as required::

    <field id="title">
    	....
        <rules>required</rules>
        ....
    </field>


Now, on the same field we define also a minimum length of 5 characters to consider it valid::

    <field id="title">
    	....
        <rules>required|min_length[5]</rules>
        ....
    </field>


---------------
Available rules
---------------
Below are described all the available rules.

^^^^^^^^
required
^^^^^^^^
Checks if the form element is empty.

^^^^^^^
matches
^^^^^^^
Checks if the form element value does not match the one in the parameter.
Usage: matches[other_fieldname]

^^^^^^^^^^^
valid_email
^^^^^^^^^^^
Checks if the form element value is not a valid email address.

^^^^^^^^^^
min_length
^^^^^^^^^^
Checks if the form element value is shorter than the parameter.
Usage: min_length[6]

^^^^^^^^^^
max_length
^^^^^^^^^^
Returns false if the form element value is longer than the parameter.
Usage: max_length[8]

^^^^^^^^^^^^
exact_length
^^^^^^^^^^^^
Checks if the form element value length is not exactly the parameter.
Usage: exact_length[4]

^^^^^^^^^^^^
greater_than
^^^^^^^^^^^^
Checks if the form element value is less than the parameter after using parseFloat.
Usage: greater_than[10]

^^^^^^^^^
less_than
^^^^^^^^^
Checks if the form element value is greater than the parameter after using parseFloat.
Usage: less_than[2]

^^^^^
alpha
^^^^^
Checks if the form element contains anything other than alphabetical characters.

^^^^^^^^^^^^^	
alpha_numeric
^^^^^^^^^^^^^
Checks if the form element contains anything other than alpha-numeric characters.

^^^^^^^^^^	
alpha_dash
^^^^^^^^^^
Checks if the form element contains anything other than alphanumeric characters, underscores, or dashes.

^^^^^^^
numeric
^^^^^^^
Checks if the form element contains anything other than numeric characters.

^^^^^^^
integer
^^^^^^^
Checks if the form element contains anything other than an integer.


Back to :doc:`fields`.