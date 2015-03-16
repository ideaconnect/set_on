*set_on* control structure
========================
**set_on** is a reversed **with** constrol structure (known from other software development environments) emulator.

Why **set_on** instead of **with**? Because the control is inversed: first we set the vars and later we execute.

Useful for usage within constructors or setter where multiple values are meant to be set.
Helps to avoid multiple **$this->** (or any different object).

Example
=======

Assuming that you have a class
````php
Class Tester {
    protected $variable1;
    protected $variable2;
    private $variable3;
    public $variable4;
}
````

and an instance of the class:
````php
$myTester = new Tester();
````

Now within for example constructor of that class you would like some variable setting to happen normally you would use:
````php
public function __construct() {
    $this->variable1 = "something";
    $this->variable2 = "somethingelse";
    $this->variable3 = "testest";
    $this->variable4 = array();
}
````

with set_on you can do:
````php
    set_on::_('variable1', "something")
            ->_('variable2', "somethingelse")
            ->_('variable3', "testtest")
            ->_('variable4', array())
            ->_($this);
````

Main use case
=============

The main use case is to set internal protected/private variables without the need of overloading any magic methods and without repeating every line *$this->*

It is bad, you know?
====================
Yes.

