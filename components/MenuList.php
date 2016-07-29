<?php
namespace app\components;

use yii\base\Component;
class MenuList extends Component {
    public $defaultLists = array();
    private $list = null;
    
    public function getList(){
        if(!$this->list) {
            $this->list = $this->defaultLists;
        }
        return $this->list;
    }
}
