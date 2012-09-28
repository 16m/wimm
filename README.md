wimm
====

When is my metro ? It's about bringing ratp.fr data to your terminal.

Introduction
------------

In a context of time optimisation, we are constantly looking for new ways to
increase productivity and to gain time. Maybe you have to take the metro at the
end of the day. What about knowing when the next trains will be at your station
with one unique command ? Do you still have the time to finish this patch ? Can
you start a new task ? To all these questions wimm tries to answer.

![Metro display](http://i.imgur.com/OcsjA.jpg "Metro display in Paris")


Configuration
-------------

### Curl dependency

Before being able to run, wimm will need the Curl dependency. After cloning
the repository, just type these 2 commands :

    $ git submodule init
    $ git submodule update

### Configuring your station

To run, wimm needs 3 informations :

 - The station name
 - The line number
 - The direction

Open wimm.php and edit this line :

```php
$Retriever->retrieve(PLACE_MONGE, 7, DIRECTION1);
```

 - Replace PLACE_MONGE by your station. All stations are available in stations.php file
 - Replace 7 by your line number
 - Choose DIRECTION1 or DIRECTION2

### Running

To display the result, just type :

   php wimm.php

![wimm in terminal](http://i.imgur.com/PAiDF.png "wimm in terminal")
