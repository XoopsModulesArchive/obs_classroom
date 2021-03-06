<?
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//  Author:  Mithrandir                                                      //

/**
 * TextfieldBlocktype class
 *
 * @package modules
 * @subpackage Classroom
 */

class TextfieldBlock extends ClassroomBlock {
    

    function TextfieldBlock() {
        $this->ClassroomBlock();
        $this->assignVar('template', 'cr_blocktype_textfield.html');
        $this->assignVar('blocktypename', 'Textfield');
        $this->assignVar('bcachetime', 2592000);
    }
    
    /**
    * Builds a form for the block
    *
    */
    function buildForm() {
        $myts = MyTextSanitizer::getInstance();
        $text = $myts->htmlSpecialChars($this->getTextfield());
        $is_edit = ($text != "") ? 1 : 0;
        include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
        $form = new XoopsThemeForm('', 'textfieldform', 'manage.php');
        $form->addElement(new XoopsFormDhtmlTextArea('Text', 'text', $text, 25, 50));
        $form->addElement(new XoopsFormHidden('op', 'editblock'));
        $form->addElement(new XoopsFormHidden('blockid', $this->getVar('blockid')));
        $form->addElement(new XoopsFormHidden('edit', $is_edit));
        $form->addElement(new XoopsFormButton('', 'submit', _CR_MA_SUBMIT, 'submit'));
        $form->display();
    }
    
    /**
    * Builds a classroomblock
    *
    * @return array
    */
    function buildBlock() {
        $myts = MyTextSanitizer::getInstance();
        $block = array();
        $text = $this->getTextfield();
        if (!$text || $text == "") {
            $text = " ";
        }
        $block['text'] = $myts->displayTarea($text, 1);
        return $block;
    }
    
    /**
    * Performs actions following buildForm submissal
    *
    */
    function updateBlock() {
        $myts = MyTextSanitizer::getInstance();
        if ($_POST['edit'] == 1) {
            $sql = "UPDATE ";
            $sql2 = "WHERE blockid=".$this->getVar('blockid');
        }
        else {
            $sql = "INSERT INTO ";
            $sql2 = ", blockid=".$this->getVar('blockid');
        }
        $sql .= $this->table." SET value='".$myts->addSlashes($_POST['text'])."'";
        $sql .= $sql2;
        if ($this->db->query($sql)) {
            return true;
        }
        return false;
    }
    
    function getTextfield() {
        $sql = "SELECT * FROM ".$this->table." WHERE blockid=".$this->getVar('blockid');
        if (!$result = $this->db->query($sql, 1, 0)) {
            return "";
        }
        
        $row = $this->db->fetchArray($result);
        return $row['value'];
    }
}
?>
