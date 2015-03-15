<?php
namespace idct;
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

        return $this;
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

        if (self::$_instance === null && (is_object($var) || is_null($var))) {
            trigger_error("Set variables first.",E_USER_ERROR);
        } else if (self::$_instance === null && is_string($var)) {
            self::$_instance = new self;
            self::$_instance->addVar($var, $val);
            return self::$_instance;
        } else if (self::$_instance !== null && is_string($var)) {
            return $this->addVar($var, $val);
        } else {
            return self::$_instance->r($var);
        }
    }

    protected function r ($object) {
        if(is_object($object)) {
            foreach($this->calls as $var => $val) {
                $attr = new ReflectionProperty(get_class($object), $var);
                if($attr->isPublic() !== true) {
                    $attr->setAccessible(true);
                    $attr->setValue($object,$val);
                }
            }
            $this->calls = array();
            return true;
        } else {
            return false;
        }
    }

}
