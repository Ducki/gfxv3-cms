<?php
 
defined('on_top') or die('You cannot access this file directly. Please go to index.php.<br />Du kannst diese Datei nicht direkt aufrufen. Bitte gehe zu index.php.');

 /**
  * tag managmet class
  * 
  * @author Chepra
  * @version 1.0.0
  * @package V3
  *
  */
class Tags  {

    /**
     * the core
     *
     * @var core
     */
    public $core;

    /**
     * Add Tags
     * 
     * Adds several tags
     *
     * @param string $tag_string The tags comma seperated
     * @param int $object_id The object id (e.g. image or article)
     * @param int $object_area The ares where the object is located (e.g. 'webauthoring' or '3d art')
     * @param int $object_module The module (like 'artikel')
     */
    public function addTags($tag_string, $object_id, $object_area, $object_module)  {
        $tags = explode(',', $tag_string);
        
        foreach($tags as $t) {
            if(!$tag_id = $this->existTag($t))    {
                $tag_id = $this->createTag($t);
            }

            $this->addTag($tag_id, $object_id, $object_area, $object_module);
        } 
    }

    /**
     * Add Tag
     * 
     * Adds one tag
     *
     * @param int $tag_id The id of the tag
     * @param int $object_id The object id (e.g. image or article)
     * @param int $object_area The ares where the object is located (e.g. 'webauthoring' or '3d art')
     * @param int $object_module The module (like 'artikel')
     * @return bool
     */
    private function addTag($tag_id, $object_id, $object_area, $object_module)   {
        return $this->core->db->insert('tags_to_objects', 
                                array(  'tag_id' => $tag_id, 
                                        'object_id' => $object_id, 
                                        'object_area' => $object_area,
                                        'object_modul' => $object_module)
                                        );
    }
    
    /**
     * Create Tag
     * 
     * Creates a new tag
     *
     * @param string $tag_name The tag name
     * @return int
     */
    private function createTag($tag_name)   {
        $this->core->db->insert('tags', array('tag_name' => $tag_name));
        
        return $this->core->db->last_insert_id();
    }

    /**
     * Delete Tag
     * 
     * Deletes a tag _complettly_ (with all objects)
     *
     * @param int $tag_id The id of the tag
     * @return bool
     */
    public function deleteTag($tag_id)    {
        return 
            $this->core->db->delete('tags', 'tag_id ='.mysql_escape_string($tag_id)) AND
            $this->core->db->delete('tags_to_objects', 
                                    'tag_id ='.mysql_escape_string($tag_id));
    }

    /**
     * Removes the Tag - Object Binding for many _Tags_
     * 
     * @param string $tag_id The id of the tag
     * @param int $object_id The id of the object
     * @param string $object_area The area we are currently working in
     */
    
    public function removeTagsFromObject($tag_string, $object_id, $object_area) {
        $tags = explode(',', $tag_string);
        
        foreach($tags as $t)    {
            $this->removeTagFromObject($t);
        }
    }

    /**
     * Removes the Tag - Object Binding
     * 
     * @param string $tag_name The name of the tag
     * @param int $object_id The id of the object
     * @param string $object_area The area we are currently working in
     * @return bool
     */
    public function removeTagFromObject($tag_name, $object_id, $object_area)    {
        $sql = "DELTE 
                    tags_to_object 
                FROM 
                    tags_to_object o , tags t 
                WHERE 
                    t.tag_id = o.tag_id 
                AND o.object_id = ".mysql_escape_string($object_id)."
                AND o.object_area = '".mysql_escape_string($object_area)."'";
                
        return $this->core->db->query($sql);
    }

    /**
     * List Tags By Element
     * 
     * Lists tags of a given element
     *
     * @param int $object_id The object id (e.g. image or article)
     * @param int $object_area The ares where the object is located (e.g. 'webauthoring' or '3d art')
     * @return array
     */
    public function listTagsByElement($object_id, $object_area)   {
         $query = "  SELECT 
                        t.tag_id, 
                        t.tag_name, 
                        o.object_id, 
                        o.object_area, 
                        o.tag_count 
                    FROM 
                        tags_to_objects o 
                    INNER JOIN 
                        tags t ON t.tag_id = o.tag_id 
                    WHERE o.object_id =".intval($object_id)." AND o.object_area = ".mysql_escape_string($object_area);
        
        $this->core->db->query($query);
        while($tag = $this->core->db->fetch())   {
            $tags[] = $tag;
        }
        
        return $tags;       
    }

    /**
     * List Elements by Tag Name
     * 
     * Lists all elements according to a given tagmhm
     *
     * @param string $tag_name The tag name
     * @param bool $object_area Only for the given area?
     * @param bool $object_module Only for one given module?
     * @param bool $ids_only Only ids instead of the whole array
     * @return array
     */
    public function listElementsByTagName($tag_name, $object_area = false, $object_module = false, $ids_only = false) {
        $query = "  SELECT 
                        t.tag_id, 
                        t.tag_name, 
                        o.object_id, 
                        o.object_area, 
                        o.tag_count 
                    FROM 
                        tags_to_objects o 
                    INNER JOIN 
                        tags t ON t.tag_id = o.tag_id 
                    WHERE MATCH(t.tag_name) AGAINST('".mysql_escape_string($tag_name)."')";

        if($object_area !== false) $query .= " AND o.object_area = '".mysql_escape_string($object_area)."'";
        if($object_module !== false) $query .= " AND o.object_modul = '".mysql_escape_string($object_module)."'";
        
        $this->core->db->query($query);
        while($object = $this->core->db->fetch())   {
            if($ids_only)   {
                $objects[] = $object['object_id'];
                
            } else {
            $objects[] = $object;
            }
        }
        
        return $objects;
    }
    
    /**
     * List Elements By Tag Id
     * 
     * Lists all elements according a given tag id
     *
     * @param int $tag_id The tag id
     * @return array
     */
    public function listElementsByTagId($tag_id)    {
        $query = "  SELECT
                        o.tag_id
                        o.object_id, 
                        o.object_area,
                        o.object_module,
                        o.tag_count
                    FROM 
                        tags_to_objects o
                    WHERE
                        o.tag_id = ".intval($tag_id);
        
        $this->core->db->query($query);
        while($object = $this->core->db->fetch())   {
            $objects[] = $object;
        }
        
        return $objects;
    }
    
    /**
     * Exist Tag
     * 
     * Checks whether a tag exists or not
     *
     * @param string $tag_name The tag name
     * @return bool
     */
    private function existTag($tag_name) {
        $this->core->db->simple_select('tag_id', 'tags', "tag_name = '".$tag_name."'");
        
        $tag_anzahl = $this->core->db->fetch();
       
        if($this->core->db->num_rows() == 0)    {
            return false;
        }
        
        return $tag_anzahl['tag_id'];
    }
}
?>