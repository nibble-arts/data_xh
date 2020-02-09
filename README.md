# CMSimple-XH Form-Plugin
The form plugin for the CMSpimle-XH framework offers a simple way to create forms, automatically fill out with data from a source likes a file or database, input data and send the result by mail, to a database backend or save it as a file. The XSL format definition makes it flexible to create simple forms, lists or print forms.

The nibble-arts memberaccess_xh is supported to add user information or restrict data access.

# New version
## Definition
A form is stored in a subdirectory with the name of the form. The form definition uses the INI-file format named form.ini with three sections:

1. fields: A list of field definitions
2. source: Source definitions for access of extern data sources to fill the form fields a data list for a select field
3. check: optional field format check routine

The ini file and format xsl files are stored in the directory. The plugin call activates the following process:

1. Load form definition (INI-file)
2. If a _self source is defined, load data
3. Create a xml file with the form definition and data
4. Load the format XSL transformation
5. Call the XSL transformation
5. Render to output

### Fields Definition Section

	input fields:
		fieldName = "input, textarea, select, checkbox, radio, hidden"

	A mandatory field is marked with a '>mandatory' at the end of the string
	fieldName = "type>mandatory"

A fixed default value can be set using '>value=string' after the type definition 

	fieldName = "type>value=default string>mandatory
	
The field name automatically is formed using the post prefix setting in three config file.
Example:

	name = "text>value=Forename>mandatory"
	
	<input type="text" name="form_name" value="Forename"/>
	
### Source Definition Section

	_self:
		The primary data for the form fields.

For linked field, like source or radiobuttons, an external source can be defined. It has to have the same fieldName.

	The source string format is:
		query@source

	Queries
		field=value

			^ boolean and
			, boolean or
			or before and, no brackets

An Asterisk as query returns all records.

		*@source
		
The values in a query string can be the value of another field, marked with the $ character before the fieldName. The source is loaded using an ajax call, when the referenced field has a value.

		field=$fieldName

The fields to be loaded from the external source are listed after a > character. The fields are returened in an array. An Astrisk returns all fields.
The field content can be rendered in a format after an additional > character. In this case a string is returned for each entry. The fieldNames can be used in the format string encapsuled in curly breakets.
	
	@source>field1,field2>Format as {field1}, {field2}

### Input Format Check Section

For each field a input format check can be added, using the same fieldName. A field check makes three field not mandatory. Two methods are available:

	count:#       Check is positive, when the number of characters is reached.
	regex:		Check is positive, when the regular expression is positive.

# Old Version

The form is defined and stored in the admin backend and used with a simple plugin call on a page.
	{{{form("form_name")}}}

# Form definition file

The form file contains a HTML code. Tags can be defined by adding a class, whitch alters the code. If a new tag is needed, a new class can be added. All tags that have no class aren't changed at all.

Each class gets the string and returns the altered string.

## Tag-Classes

### form
The form tag calls the basic class to define a form area. There can be multiple form areas, which also can be nested.
	<form name="form_name" [target="store_type: storeage_name"]>
		...
	</form>

Each form has to have a unique name. If a target is defined, the data can be send by an enclosed submit. Nested form areas without a target are used by the hide function.

### select
	<select name="name_in_post" [source="source_expression"]>
		[<option [value="send_value"]>Text</option>]
	</select>

External sources can be a file or a database call. In all cases an associative array with the values is returned. The keys are used as values.

	["key1" => "val1", ... ]

Fixed options can directly be added using option tags as children. If fixed options are combined with an external source, the fixed values are added at the beginning of the list.

#### source expression
Supported external sources are files and the database plugin.

	access:attribute
	file: field@file_name
	mysql:query_string@mysql_name

The value before the @ defines the value, field or query, the value after the character the target.

With the access type the data of the memberaccess plugin can be used when the user is logged in.

The target of a file is the file name of an ini file stored in the content area.

The target for a MySQL query is an ini file at the same directory containing the sql access data.

The files use the prefixes file. or mysql. for the corresponding source files.

	file:actor=4@file.actor.ini
	mysql:select * from actor where actor.id = 4

To use the content of a form field in the source expression, the field name has to be used with a leading $-character. The field will be dynamically updated, when the corresponding data changes.

### radio
The radio button class is defined exactly as the select class.

### checkbox
	<checkbox name="name_in_post"/>
The checkbox class creates a single checkbox.

### input
	<input name="name_in_post" [check="check_expression"] [source="source_expression"]/>
Creates a text entry field. The check expressions are used for mandatory checking. 

#### check expressions (JavaScript)
count:n -> minimum n characters needed
regex: regular expression

#### mandatory (JavaScript)
If added, the field has to be filled and
fulfill the format check.

#### hide (JavaScript)
	<... hide="name|!name|=value|!=value" ...>
The hide attribute checks the content of the field by name. This function makes it possible to structure the form and show parts depending on the input.
	name -> hide if the field is not empty
	!name -> hide if field is empty
	name = value -> hide of field value equals value
	name != value -> hide, if field value not equals value
Two or more comparisons can linked using || for a boolean or and && for a boolean and.

If the name of a form block is used, all mandatory children forks have to be true.

# File Structure
	* content
	* * plugins
	* * * form
	* * * * form_name.xml
	* * * * ...
	
	* plugins
	* * form
	* * * tag_classes
	* * * * checkbox.php
	* * * * form.php
	* * * * input.php
	* * * * select.php
	* * * * radio.php