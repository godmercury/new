<?php

class FormHelper
{
    protected $values = array();

    public function __construct($values = array())
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->values = $_POST;
        } else {
            $this->values = $values;
        }
    }

    public function input($type, $attributes = array(), $isMultiple = false)
    {
        $attributes['type'] = $type;
        if (($type === 'radio') || ($type === 'checkbox')) {
            if ($this->isOptionSelected($attributes['name'] ?? null, $attributes['value'] ?? null)) {
                $attributes['checked'] = true;
            }
            $valueAttribute = false;
        } else {
            $valueAttribute = true;
        }
        return $this->tag('input', $attributes, $isMultiple, $valueAttribute);
    }

    public function select($options, $attributes = array())
    {
        $multiple = $attributes['multiple'] ?? false;
        return
            $this->start('select', $attributes, $multiple) .
            $this->options($attributes['name'] ?? null, $options) .
            $this->end('select');
    }

    public function textarea($attributes = array())
    {
        $name = $attributes['name'] ?? null;
        $value = $this->values[$name] ?? '';
        return $this->start('textarea', $attributes) . htmlentities($value) . $this->end('textarea');
    }

    protected function tag($tag, $attributes = array(), $isMultiple = false, $valueAttribute = true)
    {
        return '<' . $tag .' ' . $this->attributes($attributes, $isMultiple, $valueAttribute) . ' />';
    }

    protected function start($tag, $attributes = array(), $isMultiple = false)
    {
        // $valueAttribute = (! (($tag === 'select') || ($tag === 'textarea')));
        $attrs = $this->attributes($attributes, $isMultiple, false);
        return '<' . $tag . ' ' . $attrs . '>';
    }

    protected function end($tag)
    {
        return '</' . $tag . '>';
    }

    protected function attributes($attributes, $isMultiple, $valueAttribute = true)
    {
        $tmp = array();

        if ($valueAttribute && isset($attributes['name']) && array_key_exists($attributes['name'], $this->values)) {
            $attributes['value'] = $this->values[$attributes['name']];
        }

         //echo '<pre>'; print_r($attributes); echo '</pre>';

        foreach ($attributes as $k => $v) {
            if (is_bool($v)) {
                if ($v) {
                    $tmp[] = $this->encode($k);
                }
            } else {
                $value = $this->encode($v);

                if ($isMultiple && ($k === 'name')) {
                    $value .= '[]';
                }
                $tmp[] = $k . '="' . $value . '"';
            }
        }

        //echo '<pre>'; print_r($tmp); echo '</pre>';

        return implode(' ', $tmp);
    }

    protected function options($name, $options)
    {
        $tmp = array();

        foreach ($options as $k => $v) {
            $s = '<option value="' . $this->encode($k) . '"';
            if ($this->isOptionSelected($name, $k)) {
                $s .= ' selected';
            }
            $s .= '>' . $this->encode($v) . '</option>';
            $tmp[] = $s;
        }

        return implode('', $tmp);
    }

    protected function isOptionSelected($name, $value)
    {
        if (! isset($this->values[$name])) {
            return false;
        } else if (is_array($this->values[$name])) {
            return in_array($value, $this->values[$name]);
        } else {
            return $value === $this->values[$name];
        }
    }

    public function encode ($html)
    {
        return htmlentities($html);
    }

}
