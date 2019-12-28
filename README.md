# CMSimple-XH Form-Plugin
The form plugin for the CMSpimle-XH framework offers a simple way to create forms, input data and send the result by mail, to a database backend or save it as a file.

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
	file: file_name
	database: field=value@table

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