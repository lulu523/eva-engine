<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

namespace Eva\Form\View\Helper;

use Zend\Form\FormInterface;
use Zend\Form\ElementInterface;

/**
 * View helper for rendering Form objects
 * 
 * @category   Zend
 * @package    Zend_Form
 * @subpackage View
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Input extends \Zend\Form\View\Helper\AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     * 
     * @param  ElementInterface $element 
     * @return string
     */
    public function __invoke(ElementInterface $element, array $options = array())
    {
        $defaultOptions = array(
            'type' => 'formInput',
            'args' => array(),
        );

        $options = array_merge($defaultOptions, $options);
        $elementType = $options['type'];
        unset($options['type']);

        //Support Subform
        if($subFormName = $element->getAttribute('data-subform-name')){
            $name = $element->getName();
            if($name){
                $element->setName($subFormName . '[' . $name . ']');
                $attributes = $element->getAttributes();
                //Reset attributes to make sure re-name just once;
                unset($attributes['data-subform-name']);
                $element->clearAttributes();
                $element->setAttributes($attributes);
            }
        }

        $args = array();
        if(isset($option['args'])){
            if($option['args'] && is_array($option['args'])){
                foreach($args as $key => $value){
                    $args[] = $value; 
                }
            }
            unset($option['args']);
        }

        if($options){
            //NOTE: clone element not effect to form original element
            $element = clone $element;
            foreach($options as $key => $value){
                $element->setAttribute($key, $value);
            }
        }

        //put element clone into view helper
        array_unshift($args, $element);

        $view = $this->getView();
        return call_user_func_array(array(
            &$view,
            $elementType,
        ), $args);
        //return $view->$elementType($element);
    }
}
