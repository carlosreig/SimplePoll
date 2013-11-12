SimplePoll
==========

A simple poll creator. You only have to describe the poll fields in an XML field and SimplePoll will do the rest for you.

ADVERTISMENT
-------------
This project is under development and it is not already working as it should do. 

Instalation
--------------
You only need [Composer](http://getcomposer.org/) installed in your system and write
```
composer.phar update
```

Basics
---------
This project allow you to create a poll very easily. You only have to describe wich fields your poll will have in an XML document and run:
```
$poll = new SimplePoll();
$poll->loadPoll('poll.xml');
```
With these lines, SimplePoll has read the document which describes the poll questions and it is ready for render the poll with the next line:

```
$poll->renderPoll('poll.html');
```

This line indicates to SimplePoll where the poll template is. I am using [Twig](http://twig.sensiolabs.org/) and [Twitter Bootstrap](http://getbootstrap.com/) as templating system and front-end-framework, so is **very easy** configure your own and beautiful poll template.

TODO
-------
I have planned three stages for this project: data recollect, data storage and data viewing

Now, we are the first part of the data recollect stage. In this stage we should have a system who allows to:
*	Define pools in an XML format (done)
*	Present the defined pools to the user (we are here)
*	Allow the user to fill the poll

In the data storage stage we should have a system who allows to:
*	Validate the info retrieved by a pool
*	Store it in a DB

And in the data viewing stage we should be able to:
*	Recollect the poll data from the database
*	Present a complete report to the poll creator