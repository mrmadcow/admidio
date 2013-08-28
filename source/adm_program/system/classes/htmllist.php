<?php
/*****************************************************************************/
/** @class HtmlList
 *  @brief  Create html lists
 * 
 *  This class creates html list elements.
 *  Create a list object for ordered, unordered or datalist an add the list items.
 *  The class supports datalists and ordered/unorderedlists and combinations of nested lists and/or datalists.
 *  The parsed list object is returned as string.
 *  
 *  @par Example 1: Creating a datalist
 *  @code
 *  // Get instance
 *  $list = new HtmlList('dl', 'id_dl', 'class'); // Parameter for list type, id and class are optional ( Default list type = ul )
 *  // add  2 list items term and description as string. Arrays are not supported!
 *  $list->addDataListItems('term_1', 'Listdata_1');
 *  $list->addDataListItems('term_2', 'Listdata_2');
 *  // get parsed datalist as string
 *  echo $list->getHtmlList();
 *  @endcode
 *  @par Example 2: Creating  ordered list
 *  @code
 *  // Get instance
 *  $list = new HtmlList('ol', 'id_ol', 'class');
 *  // Set type Attribute 
 *  $list->addAttribute('type', 'square');
 *  // Define a list element with ID "Item_0" and data "Listdata_1" as string
 *  $list->addListItem('Item_0', 'Listdata_1');
 *  // Next list elements: Defining a term, a datalist is automatically nested in the list element
 *  $list->addListItem('Item_1', 'Listdata_2', 'term_2');
 *  $list->addListItem('Item_2', 'Listdata_3', 'term_3');
 *  $list->addListItem('Item_3', 'Listdata_4', 'term_4');
 *  // Also manually configuration is possible
 *  // Define list element "li" with attribute ID = Item_5
 *  $list->addListItem('Item_5');
 *  // Define datalist in link element
 *  $list->addDataList();
 *  // Define several terms and descriptions of the data list
 *  $list->addDataListItems('term_5', 'Listdata_5');
 *  list->addDataListItems('term_5.1', 'Listdata_5.1');
 *  list->addDataListItems('term_5.2', 'Listdata_5.2');
 *  // get parsed datalist as string
 *  echo $list->getHtmlList();
 *  @endcode
 */
/*****************************************************************************
 *
 *  Copyright    : (c) 2004 - 2013 The Admidio Team
 *  Author       : Thomas-RCV
 *  Homepage     : http://www.admidio.org
 *  License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 *
 *****************************************************************************/

class HtmlList extends HtmlElement 
{
    
    /**
     * Constructor creates the element
     *
     * @param $list List element ( ul/ol/dl Default: ul) 
     * @param $id Id of the list
     * @param $class Class name of the list
     */
     
    public function __construct($list = 'ul', $id = '', $class = '')
    {        
        
        parent::__construct($list, '', '', true);
        
        if(strlen($id) > 0)
        {
            $this->addAttribute('id', $id);
        }
        
        if(strlen($class) > 0)
        {
            $this->addAttribute('class', $class);
        }
    } 

    /**
     *  @par Add a datalist (dl).
     *
     *  @param $id Id Attribute
     *  @param $term Term as string for datalist
     *  @param $description Description as string for data 
     */
    public function addDatalist($id = '', $term = null, $description = null)
    {
        // First check whether open list item tag  must be closed before setting new item
        if(in_array('dl', $this->arrParentElements))
        {
            $this->closeParentElement('dl');
        }
        $this->addParentElement('dl');
        
            if(strlen($id) > 0)
        {
            $this->addAttribute('id', $id);
        }
        
        if($term != null && $description != null)
        {
            $this->addDataListItems($term, $description);
        }
    }
    
    /**
     *  @par Add term and description to datalist (dl).
     *
     *  @param $term Term as string for datalist
     *  @param $description Description as string for data 
     */
    public function addDataListItems($term = null, $description = null)
    {
        if($term != null && $description != null)
        {
            // Arrays are not supported in datalists
            if(!is_array($term) && !is_array($description))
            { 
                $this->addElement('dt', '', '', $term);
                $this->addElement('dd', '', '', $description);
            }
            else
            {
                // Arrays are not supported
                throw new exception('Arrays are not supported in datalist items! Items are determined as string!');
            }
        }
        return false;
    }
    
    /**
     *  @par Add a list item (li).
     *
     *  @param $id id Attribute
     *  @param $data element data
     *  @param $term optional term as string for nested datalist
     */
    public function addListItem($id = '', $data = null, $term = null)
    {
        if($data != null && $term != null)
        {
            // First check whether open list item tag  must be closed before setting new item
            if(in_array('li', $this->arrParentElements))
            {
                $this->closeParentElement('li');
            }
    
            // Set new item
            $this->addParentElement('li');
    
            if(strlen($id) > 0 )
            {
                $this->addAttribute('id', $id);
            }
                 
            // Define datalist with term and data as description
            $this->addDataList('', $term, $data);
        }
        else
        {
            if($data != null)
            {   
                $this->addElement('li');
                
                    if(strlen($id) > 0 )
                {
                    $this->addAttribute('id', $id);
                }
                
                $this->addData($data);
            }
            else
            {
                // handle as parent element maybe a datalist could be nested next
                $this->addParentElement('li');
                
                if(strlen($id) > 0 )
                {
                    $this->addAttribute('id', $id);
                }

            }    
        }           
    }
    
    /**
     * Get the parsed html list
     *
     * @return Returns the validated html list as string
     */
    public function getHtmlList()
    {
        $this->closeParentElement('.$this->currentElement().');
        return parent::getHtmlElement();
    }

} 

?>
