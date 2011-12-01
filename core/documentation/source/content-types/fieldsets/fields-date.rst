====================
Date and Time fields
====================

Using the **Date** fields, you can display an input with a datepicker plugin to let the user choose a date.

The **Datetime** fields is similar, because it just adds a number field on the right to specify also the time.

These fields are declared as **date** and *datetime**.

Below you can find a sample implementation::

    <field id="date_publish" column="true">
        <description>Visibility date</description>
        <type>datetime</type>
        <list>true</list>
    </field>

Inside of the framework, these fields will be saved using the **UNIX timestamp** format.

You can access that timestamp by adding the underscore "_" prefix to the field name::

    //Displays the datetime using the local format (eg: dd/mm/YYYY HH:ii)
    echo $record->get('date_publish');

    //Displays the timestamp
    echo $record->get('_date_publish');

Back to :doc:`fields`.