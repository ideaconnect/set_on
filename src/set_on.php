<?php
namespace IDCT;
/**
 * set_on is a reversed 'with' constrol structure (known from other software development environments) emulator.
 *
 * Why 'set_on' instead of 'with'? Because the control is inversed: first we set the vars and later we execute.
 *
 * Useful for usage within constructors or setter where multiple values are meant to be set.
 * Helps to avoid multiple $this-> (or any different object) *
 *
 * # Example:
 *
 * Assuming that you have a class
 *
 * Class Tester {
 *     protected $variable1;
 *     protected $variable2;
 *     private $variable3;
 *     public $variable4;
 * }
 *
 * and an instance of the class:
 * $myTester = new Tester();
 *
 * You can call:
 *
 * set_on::_('variable1', $someValue)
 *       ->_('variable2', 'Test Value')
 *       ->_('variable3', $someValue2)
 *       ->_('variable4', 43134)
 *       ->_($myTester);
 *
 * @author Bartosz Pacho?ek <bartosz@idct.pl>
 */
class set_on {

    protected $object;
    protected $calls = array();

    static protected $_instance = null;

    /**
     * Method which adds the variable to the collection which will be executed when an object is passed.
     *
     * Public for usage by the static construction on first use.
     * @param string $var
     * @param mixed $val
     * @return self
     */
    public function addVar($var, $val) {
        $this->calls[$var] = $val;
    }

    /**
     * Main method
     *
     * Use with a mix of string -> value to set the variable or with an object to execute the previously set variables
     *
     * @param string|object $var
     * @param mixed $val
     * @return boolean|self
     */
    public function _ ($var = null, $val = null) {
        if(!(isset($this) && get_class($this) == __CLASS__)) {
            //we entered from static context
            self::$_instance = new self;
            $me = self::$_instance;
        } else {
            //we entered via instance
            $me = $this;
        }

        if(is_string($var) && !empty($var)) {
            $me->addVar($var, $val);
            return $me;
        } else if (is_object($var)) {
            return $me->r($var);
        }

    }

    protected function r ($depedencyObject) {
        if(is_object($depedencyObject)) {
            foreach($this->calls as $var => $val) {
                $attr = new \ReflectionProperty(get_class($depedencyObject), $var);
                if($attr->isPublic() !== true) {
                    $attr->setAccessible(true);
                    $attr->setValue($depedencyObject,$val);
                }
            }
            $this->calls = array();
            return true;
        } else {
            return false;
        }
    }

}
