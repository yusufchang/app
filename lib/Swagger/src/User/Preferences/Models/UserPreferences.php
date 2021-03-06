<?php
/**
 * UserPreferences
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
/**
 *  Copyright 2015 SmartBear Software
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\User\Preferences\Models;

use \ArrayAccess;
/**
 * UserPreferences Class Doc Comment
 *
 * @category    Class
 * @description 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class UserPreferences implements ArrayAccess
{
    /**
      * Array of property to type mappings. Used for (de)serialization 
      * @var string[]
      */
    static $swaggerTypes = array(
        'global_preferences' => '\Swagger\Client\User\Preferences\Models\GlobalPreference[]',
        'local_preferences' => '\Swagger\Client\User\Preferences\Models\LocalPreference[]'
    );
  
    /** 
      * Array of attributes where the key is the local name, and the value is the original name
      * @var string[] 
      */
    static $attributeMap = array(
        'global_preferences' => 'globalPreferences',
        'local_preferences' => 'localPreferences'
    );
  
    /**
      * Array of attributes to setter functions (for deserialization of responses)
      * @var string[]
      */
    static $setters = array(
        'global_preferences' => 'setGlobalPreferences',
        'local_preferences' => 'setLocalPreferences'
    );
  
    /**
      * Array of attributes to getter functions (for serialization of requests)
      * @var string[]
      */
    static $getters = array(
        'global_preferences' => 'getGlobalPreferences',
        'local_preferences' => 'getLocalPreferences'
    );
  
    
    /**
      * $global_preferences 
      * @var \Swagger\Client\User\Preferences\Models\GlobalPreference[]
      */
    protected $global_preferences;
    
    /**
      * $local_preferences 
      * @var \Swagger\Client\User\Preferences\Models\LocalPreference[]
      */
    protected $local_preferences;
    

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        if ($data != null) {
            $this->global_preferences = $data["global_preferences"];
            $this->local_preferences = $data["local_preferences"];
        }
    }
    
    /**
     * Gets global_preferences
     * @return \Swagger\Client\User\Preferences\Models\GlobalPreference[]
     */
    public function getGlobalPreferences()
    {
        return $this->global_preferences;
    }
  
    /**
     * Sets global_preferences
     * @param \Swagger\Client\User\Preferences\Models\GlobalPreference[] $global_preferences 
     * @return $this
     */
    public function setGlobalPreferences($global_preferences)
    {
        
        $this->global_preferences = $global_preferences;
        return $this;
    }
    
    /**
     * Gets local_preferences
     * @return \Swagger\Client\User\Preferences\Models\LocalPreference[]
     */
    public function getLocalPreferences()
    {
        return $this->local_preferences;
    }
  
    /**
     * Sets local_preferences
     * @param \Swagger\Client\User\Preferences\Models\LocalPreference[] $local_preferences 
     * @return $this
     */
    public function setLocalPreferences($local_preferences)
    {
        
        $this->local_preferences = $local_preferences;
        return $this;
    }
    
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset 
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
  
    /**
     * Gets offset.
     * @param  integer $offset Offset 
     * @return mixed 
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }
  
    /**
     * Sets value based on offset.
     * @param  integer $offset Offset 
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }
  
    /**
     * Unsets offset.
     * @param  integer $offset Offset 
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
  
    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) {
            return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
        } else {
            return json_encode(get_object_vars($this));
        }
    }
}
