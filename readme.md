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

You can call:
````php
    set_on::_('variable1', $someValue)
            ->_('variable2', 'Test Value')
            ->_('variable3', $someValue2)
            ->_('variable4', 43134)
            ->_($myTester);
````

It is bad, you know?
====================
Yes.

