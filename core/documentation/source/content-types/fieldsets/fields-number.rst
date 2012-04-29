==============
Numeric fields
==============

Similar to the **text** fields, this field let you enter some text but is limited to numbers.
On latest HTML5 compatible browsers, an increment/decrement switch will also appears.

Simple usage using YAML::

	year :
  		description : Year
  		type        : number
  		rules       : required|max_length[4]

or using XML::

    <field id="year">
        <type>number</type>
        <description>Year</description>
        <rules>required|max_length[4]</rules>
    </field>

Back to :doc:`fields`.