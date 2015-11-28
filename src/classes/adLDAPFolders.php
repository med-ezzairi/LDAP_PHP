<?php
/**
 * PHP LDAP CLASS FOR MANIPULATING ACTIVE DIRECTORY 
 * Version 4.0.4
 * 
 * PHP Version 5 with SSL and LDAP support
 * 
 * Written by Scott Barnett, Richard Hyland
 *   email: scott@wiggumworld.com, adldap@richardhyland.com
 *   http://adldap.sourceforge.net/
 * 
 * Copyright (c) 2006-2012 Scott Barnett, Richard Hyland
 * 
 * We'd appreciate any improvements or additions to be submitted back
 * to benefit the entire community :)
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * @category ToolsAndUtilities
 * @package adLDAP
 * @subpackage Folders
 * @author Scott Barnett, Richard Hyland
 * @copyright (c) 2006-2012 Scott Barnett, Richard Hyland
 * @license http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPLv2.1
 * @revision $Revision: 97 $
 * @version 4.0.4
 * @link http://adldap.sourceforge.net/
 */
require_once(dirname(__FILE__) . '/../adLDAP.php');

/**
* FOLDER / OU MANAGEMENT FUNCTIONS
*/
class adLDAPFolders {
    /**
    * The current adLDAP connection via dependency injection
    * 
    * @var adLDAP
    */
    protected $adldap;
    
    public function __construct(adLDAP $adldap) {
        $this->adldap = $adldap;
    }
    
    /**
    * Delete a distinguished name from Active Directory
    * You should never need to call this yourself, just use the wrapper functions user_delete and contact_delete
    *
    * @param string $dn The distinguished name to delete
    * @return bool
    */
    public function delete($dn){
        if($dn==NULL) return false;
        $result = ldap_delete($this->adldap->getLdapConnection(), $dn);
        if ($result != true) { 
            return false; 
        }
        return true;
    }
    
    /**
    * move a folder form container to another
    * 
    *
    * @param string $folder name of the folder
    * @param straing $container the dn to the new conainer
    * @return bool
    */
    public function move($folder,$container){
        if (!$this->adldap->getLdapBind()) { return false; }
        if ($folder === null) { return false; }
        if ($container === null) { return false; }

        $newRDn = "ou=" . $this->adldap->getName($folder);
        $newBaseDn = strtolower($container);
        $result = @ldap_rename($this->adldap->getLdapConnection(), $folder, $newRDn, $newBaseDn, true);

        if ($result != true) { 
            return false; 
        }
        return true;
    }
    /**
    * Returns a folder listing for a specific OU
    * See http://adldap.sourceforge.net/wiki/doku.php?id=api_folder_functions
    * 
    * @param array $folderName An array to the OU you wish to list. 
    *                           If set to NULL will list the root, strongly recommended to set 
    *                           $recursive to false in that instance!
    * @param string $dnType The type of record to list.  This can be ADLDAP_FOLDER or ADLDAP_CONTAINER.
    * @param bool $recursive Recursively search sub folders
    * @param bool $type Specify a type of object to search for
    * @return array
    */
    public function listing($folderName = NULL, $dnType = adLDAP::ADLDAP_FOLDER, $recursive = NULL, $type = NULL) 
    {
        if ($recursive === NULL) { $recursive = $this->adldap->getRecursiveGroups(); } //use the default option if they haven't set it
        if (!$this->adldap->getLdapBind()) { return false; }

        $filter = '(&';
        if ($type !== NULL) {
            switch ($type) {
                case 'contact':
                    $filter .= '(objectClass=contact)';
                    break;
                case 'computer':
                    $filter .= '(objectClass=computer)';
                    break;
                case 'group':
                    $filter .= '(objectClass=group)';
                    break;
                case 'folder':
                    $filter .= '(objectClass=organizationalUnit)';
                    break;
                case 'container':
                    $filter .= '(objectClass=container)';
                    break;
                case 'domain':
                    $filter .= '(objectClass=builtinDomain)';
                    break;
                default:
                    $filter .= '(objectClass=user)';
                    break;   
            }
        }
        else {
            $filter .= '(objectClass=*)';   
        }
        // If the folder name is null then we will search the root level of AD
        // This requires us to not have an OU= part, just the base_dn
        $searchOu = $this->adldap->getBaseDn();
        if (is_array($folderName)) {
            $ou = $dnType . "=" . implode("," . $dnType . "=", $folderName);
            $filter .= '(!(distinguishedname=' . $ou . ',' . $this->adldap->getBaseDn() . ')))';
            $searchOu = $ou . ',' . $this->adldap->getBaseDn();
        }
        else {
            $filter .= '(!(distinguishedname=' . $this->adldap->getBaseDn() . ')))';
        }

        if ($recursive === true) {
            $sr = ldap_search($this->adldap->getLdapConnection(), $searchOu, $filter, array('objectclass', 'distinguishedname', 'samaccountname'));
            $entries = @ldap_get_entries($this->adldap->getLdapConnection(), $sr);
            if (is_array($entries)) {
                return $entries;
            }
        }
        else {
            $sr = ldap_list($this->adldap->getLdapConnection(), $searchOu, $filter, array('objectclass', 'distinguishedname', 'samaccountname'));
            $entries = @ldap_get_entries($this->adldap->getLdapConnection(), $sr);
            if (is_array($entries)) {
                return $entries;
            }
        }
        
        return false;
    }

    /**
    * Create an organizational unit
    * 
    * @param array $attributes Default attributes of the ou
    * @return bool
    */
    public function create($attributes)
    {
        if (!is_array($attributes)){ return "Attributes must be an array"; }
        if (!array_key_exists("ou_name",$attributes)) { return "Missing compulsory field [ou_name]"; }
        if (!array_key_exists("container",$attributes)) { return "Missing compulsory field [container]"; }
        


        $add=array();
        $add["objectClass"] = "organizationalUnit";
        $add["OU"] = $attributes['ou_name'];
        if (array_key_exists("description",$attributes) && !empty($attributes['description'])){
            $add["description"] = $attributes['description'];
        }
        $container = $attributes["container"];
        $result = ldap_add($this->adldap->getLdapConnection(), "OU=" . $add["OU"] . "," . $container, $add);
        if ($result != true) { 
            return false; 
        }
        
        return true;
    }
    
}

?>