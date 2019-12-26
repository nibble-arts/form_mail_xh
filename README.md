# form-plugin
The form plugin for the CMSpimle-XH framework offers a simple way to create forms to input data and send the by mail, to a database backend or save it as a file.

The form is defined and stored in the admin backend and used with a simple plugin call on a page.
	{{{form("form_name")}}}

# Form definition file

The file contains basically a HTML code. Tags can be defined by adding class, which alert the code. If a new tag is needed, a new class can be added.

Each class gets the string and returns the altered string.

## classes:

## select
	<select name="name_in_post" [source="external_source"]>
		[<option [value="send_value"]>Text</option>]
	</select>

External sources can be a file or a database call. In all cases an associative array with the values is returned. The keys are used as values.

## radio
The radio button class is defined exactly as the select class.

## checkbox
	<checkbox name="name_in_post"/>
The checkbox class creates a single checkbox.

## input
	<input name="name_in_post" [check="check_expression"]/>
Creates a text entry field. The check expressions are used for mandatory checking.

### check expressions
count:n -> minimum n characters needed
regex: regular expression

## mandatory
If added, the field has to be filled and
fulfill the format check.

## hide
	<... hide="name|!name|=value|!=value" ...>
The hide attribute checks the content of the field by name.
name -> hide if the field is not empty
!name -> hide if field is empty
name = value -> hide of field value equals value
name != value -> hide, if field value not equals value