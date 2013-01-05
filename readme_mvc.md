The purpose of the mvc branch is to experiment with (aka improve) the Joomla MVC model - without going too far.

There are some ideas that IMHO are worth thinking about while others may need some more time to actually be useful or probably might turn out complete nonsense ;)

So I was taking the tracker app as an experimental field also to get the chance to discuss those ideas with other persons that may be involved in coding matters.

I'll try to explain:

## CMS Extension Autoloader
This class loads Joomla! CMS extension classes based on their name.

The naming concept is:

* Class: `ComFooModelBar`
    File:  `JROOT/components/com_foo/model/bar.php`
* Class: `ComAdminFooModelBar`
    File: `JROOT/administrator/components/com_foo/model/bar.php`
* Class: `ModAdminFooHelper`
    File: `JROOT/administrator/modules/mod_foo/helper/helper.php`
etc.

**Pros:**

* Any extension class can be loaded in any place.
    POC: a bit extreme, but the tracker CLI app that retrieves the issues may use the com_tracker admin model to retrieve a list of projects.
    See: https://github.com/JTracker/jissues/blob/mvc/cli/retrieveissues.php#L76

**Cons:**

* All classes have to be renamed and/or moved to satisfy the new naming concept.
* Speed ?

**Refs:**

* https://github.com/JTracker/jissues/blob/mvc/libraries/tracker/cms/loader.php

## Default classes for views, models, controllers and tables

Default classes will be used if not provided by the component.

POC: admin/com_cpanel uses only one PHP layout file: https://github.com/JTracker/jissues/tree/mvc/trkadmin/components/com_cpanel

## Controllers
I am trying the following approach:

Controller name based on the current "task" (variable from request)

This task *might be* in two parts separated by a dot e.g. `controller.action` - similar like it was "before" but with the difference, that the `controller` part now points to a "group"

Example:

* `option=com_foo&task=project.save`
* will execute the `execute()` method in `ComFooControllerProjectSave`
* wich lives in `JROOT/components/com_foo/controller/project/save.php`

## JTable
A small add on that should save headache..

The field name `id` is now reserved and used as an alias to the actual `primary key` of the table. So the "urlvar" used in requests may be dropped.

**Refs:**

* https://github.com/JTracker/jissues/blob/mvc/libraries/tracker/table/default.php

## JForm
Some attempts to bring in JForm without using the legacy classes:

* https://github.com/JTracker/jissues/tree/mvc/libraries/tracker/controller/form

## Entry point
This is the first file called when executing a component.

Historically this was the most important file - that is "the time before MVC arrived".

Later, this file was used to instantiate a controller - somewhat defining that this is a "MVC component"

I think it's save to assume, that Joomla! components **must** be MVC. Period.

So the "entry point" might be also obsolete.

If you still want to build a monolithic, single file, thingy, you have to place it in a `view/default/tmpl/default.php` file.

If you need an entry point that does some stuff for every call of your extension you have to name it `component.php`. Don't mess with controllers here ;)

So - this will be for now the way to decide if a component will be rendered in "legacy mode", which is still possible.

## Renaming the administrator folder
This is a thing often discussed as a security feature. While I'm not completely convinced, I think it's a good proof that the application is "portable".

But most important: Because we can :)

## Language system
This is the most experimental part and is definitely not ready for merging.

The idea is to write the language strings in PHP code in "clear text" and passing it to a method in the base class that adds one or more prefixes.

JLanguage should store than the keys in an indexed array based on the extension.

For now I have just a dummy function: https://github.com/JTracker/jissues/blob/mvc/libraries/tracker/view/default/html.php#L58
And the implementation looks like this: https://github.com/JTracker/jissues/blob/mvc/trkadmin/components/com_tracker/view/default/tmpl/project.php#L13

That's all I can think of right now - beside some "smaller changes" like a language selector etc.

Open for discussion :)
