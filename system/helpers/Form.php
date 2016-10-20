<?php defined('DIRECT') OR exit('No direct script access allowed');
/**
 * Form Helper
 *
 * Turan Karatuğ - <tkaratug@hotmail.com.tr>
 */

/**
 * Form open
 * @param   string|array    $name
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_open') ) {
	function form_open($name = '', $attr = [])
	{
	    if(is_array($name)) {
	        $form = '<form ';
	        if(count($name) > 0) {
	            foreach($name as $key => $val)
	            $form .= $key . '="' . $val . '" ';
	        }
	    } else {
	        $form = '<form name="' . $name . '" id="' . $name . '" ';

	        if(count($attr) > 0) {
	            foreach($attr as $key => $val) {
	                $form .= $key . '="' . $val . '" ';
	            }
	        }            
	    }

	    $form = trim($form);
	    $form .= '>';

	    return $form . "\n";
	}
}

/**
 * Text input
 * @param   string|array    $name
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_input') ) {
	function form_input($name = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<input ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="text" name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Password input
 * @param   string|array    $name
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_password') ) {
	function form_password($name = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<input type="password" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="password" name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }    
        }

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Hidden input
 * @param   string|array    $name
 * @param   string          $value
 * @return  string
 */
if ( ! function_exists('form_hidden') ) {
	function form_hidden($name = '', $value = '')
    {
        if(is_array($name)) {
            $element = '<input type="hidden" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
            $element = trim($element);
            $element .= '>';
        } else {
            $element = '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '">';
        }

        return $element . "\n";
    }
}

/**
 * File input
 * @param   string|array    $name
 * @param   bool            $multiple
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_file') ) {
	function form_file($name = '', $multiple = false, $attr = [])
    {
        if(is_array($name)) {
            $element = '<input type="file" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            if($multiple == true)
                $element = '<input type="file" name="' . $name . '[]" id="' . $name . '[]" multiple="multiple" ';
            else
                $element = '<input type="file" name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Custom input
 * @param   string|array    $type
 * @param   string          $name
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_custom') ) {
	function form_custom($type = '', $name = '', $attr = [])
    {
        if(is_array($type)) {
            $element = '<input ';
            if(count($type) > 0) {
                foreach($type as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Textarea
 * @param   string|array    $name
 * @param   string          $text
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_textarea') ) {
	function form_textarea($name = '', $text = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<textarea ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<textarea name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>' . $text . '</textarea>';

        return $element . "\n";
    }
}

/**
 * SelectBox
 * @param   string|array    $name
 * @param   array           $options
 * @param   string          $selected
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_select') ) {
	function form_select($name = '', $options = [], $selected = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<select ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<select name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        $dropdown = '';
        if(count($options) > 0) {
            foreach($options as $key => $val) {
                if($selected && $val == $selected)
                    $dropdown .= '<option vaue="' . $val . '" selected>' . $key . '</option>';
                else
                    $dropdown .= '<option vaue="' . $val . '">' . $key . '</option>';
            }
        }

        return $element . "\n" . $dropdown . "\n" . '</select>';
    }
}

/**
 * Multiple SelectBox
 * @param   string|array    $name
 * @param   array           $options
 * @param   string          $selected
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_multiSelect') ) {
	function form_multiSelect($name = '', $options = [], $selected = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<select multiple="multiple" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<select name="' . $name . '" id="' . $name . '" multiple="multiple" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        $dropdown = '';
        if(count($options) > 0) {
            foreach($options as $key => $val) {
                if($selected && $val == $selected)
                    $dropdown .= '<option vaue="' . $val . '" selected>' . $key . '</option>';
                else
                    $dropdown .= '<option vaue="' . $val . '">' . $key . '</option>';
            }
        }

        return $element . "\n" . $dropdown . "\n" . '</select>';
    }
}

/**
 * Checkbox
 * @param   string|array    $name
 * @param   string          $value
 * @param   bool            $checked
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_checkbox') ) {
	function form_checkbox($name = '', $checked = false, $value = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<input type="checkbox" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="checkbox" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }
        
        if($checked == true)
                $element .= 'checked';

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Radio button
 * @param   string|array    $name
 * @param   string          $value
 * @param   bool            $checked
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_radio') ) {
	function form_radio($name = '', $checked = false, $value = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<input type="radio" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="radio" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }
        
        if($checked == true)
                $element .= 'checked';
        
        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Form submit
 * @param   string|array    $name
 * @param   string          $value
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_submit') ) {
	function form_submit($name = '', $value = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<input type="submit" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="submit" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Form button
 * @param   string|array    $name
 * @param   string          $content
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_button') ) {
	function form_button($name = '', $content = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<button type="button" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<button type="button" name="' . $name . '" id="' . $name . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>' . $content . '</button>';

        return $element . "\n";
    }
}

/**
 * Form reset button
 * @param   string|array    $name
 * @param   string          $value
 * @param   array           $attr
 * @return  string
 */
if ( ! function_exists('form_reset') ) {
	function form_reset($name = '', $value = '', $attr = [])
    {
        if(is_array($name)) {
            $element = '<input type="reset" ';
            if(count($name) > 0) {
                foreach($name as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        } else {
            $element = '<input type="reset" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $element .= $key . '="' . $val . '" ';
                }
            }
        }

        $element = trim($element);
        $element .= '>';

        return $element . "\n";
    }
}

/**
 * Form label
 * @param   string  $for
 * @param   string  $content
 * @param   array   $attr
 * @return  string
 */
if ( ! function_exists('form_label') ) {
	function form_label($for = '', $content = '', $attr = [])
    {
        if(is_array($for)) {
            $label = '<label ';
            if(count($for) > 0) {
                foreach($for as $key => $val) {
                    $label .= $key . '="' . $val ."' ";
                }
            }
        } else {
            $label = '<label for="' . $for . '" ';

            if(count($attr) > 0) {
                foreach($attr as $key => $val) {
                    $label .= $key . '="' . $val . '" ';
                }
            }
        }

        $label = trim($label);
        $label .= '>' . $content . '</label>';

        return $label . "\n";
    }
}

/**
 * Form close
 * @return  string
 */
if ( ! function_exists('form_close') ) {
	function form_close()
    {
        return '</form>';
    }
}

/**
 * CSRF Token Generate
 * @return string
 */
if ( ! function_exists('csrf_generate') ) {
    function csrf_generate()
    {
        $titan = Loader::getInstance();
        $titan->plugin('session');
        $titan->session->set('titan_token', base64_encode(openssl_random_pseudo_bytes(32)));
        return $titan->session->get('titan_token');
        
    }
}

/**
 * CSRF Token Check
 * @param $token
 * @return boolean
 */
if ( ! function_exists('csrf_check') ) {
    function csrf_check($token)
    {
        $titan = Loader::getInstance();
        $titan->plugin('session');
        
        if ($titan->session->is_exists('titan_token') && $token === $titan->session->get('titan_token')) {
            $titan->session->delete('titan_token');
            return true;
        }
        return false;
    }
}