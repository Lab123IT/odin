<?php
namespace Lab123\Odin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class FormRequest extends FormRequest
{

    /**
     * General rules to validate this request.
     */
    protected $rules = [];

    /**
     * Rules to validate in this request.
     */
    protected $validateFields = [];

    /**
     * Custom rules to validate in this request.
     */
    protected $customRules = [];

    /**
     * Override rules to validate in this request.
     */
    protected $overrideRules = [];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->customValidates();
        $this->overrideValidates();
        
        return array_only($this->rules, $this->validateFields);
    }

    /**
     * Set the validation rules that apply to the request.
     *
     * @return array
     */
    private function customValidates()
    {
        if (count($this->customRules) > 0) {
            foreach ($this->customRules as $field => $value) {
                if (empty($value) || is_int($field)) {
                    continue;
                }
                
                $this->rules[$field] .= $value;
            }
        }
    }

    /**
     * Set the validation rules that apply to the request.
     *
     * @return array
     */
    private function overrideValidates()
    {
        if (count($this->overrideRules) > 0) {
            foreach ($this->overrideRules as $field => $value) {
                if (empty($value) || is_int($field)) {
                    continue;
                }
                
                $this->rules[$field] = $value;
            }
        }
    }
}